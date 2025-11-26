<script setup>
import { router } from '@inertiajs/vue3'

const props = defineProps({
    appointments: Array,
    doctors: Array,
})
</script>

<template>
    <div class="p-8">
        <h1 class="text-3xl font-bold mb-8 text-gray-800">Gestión de Citas Médicas</h1>

        <!-- Botón de nueva cita y ver agenda por médico -->
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <select v-model="selectedDoctor" class="border rounded px-3 py-2">
                    <option value="">Seleccione médico...</option>
                    <option v-for="d in doctors" :key="d.id" :value="d.id">{{ d.name }} — {{ d.specialty }}</option>
                </select>
                <button @click.prevent="applyFilter" class="px-3 py-2 bg-gray-200 rounded">Buscar</button>
                </div>

            <div>
                
            </div>
        </div>

        <!-- Tabla de citas -->
        <div class="overflow-x-auto bg-white rounded-2xl shadow-lg">
            <table class="min-w-full table-auto border-collapse">
                <thead class="bg-gray-100">
                    <tr class="text-left text-gray-700 uppercase text-sm font-semibold">
                        <th class="px-6 py-4">Paciente</th>
                        <th class="px-6 py-4">Correo</th>
                        <th class="px-6 py-4">Doctor</th>
                        <th class="px-6 py-4">Fecha y hora</th>
                        <th class="px-6 py-4">Estado</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="appointment in appointments"
                        :key="appointment.id"
                        class="border-b hover:bg-gray-50 transition"
                    >
                        <td class="px-6 py-4">{{ appointment.patient_name }}</td>
                        <td class="px-6 py-4">{{ appointment.patient_email }}</td>
                        <td class="px-6 py-4">{{ appointment.doctor?.name || '—' }}</td>
                        <td class="px-6 py-4">
                            {{ new Date(appointment.start_time).toLocaleString() }}
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="px-3 py-1 rounded-full text-sm font-semibold"
                                :class="{
                                    'bg-yellow-100 text-yellow-800': appointment.status === 'pendiente',
                                    'bg-green-100 text-green-800': appointment.status === 'confirmada',
                                    'bg-red-100 text-red-800': appointment.status === 'rechazada',
                                    'bg-blue-100 text-blue-800': appointment.status === 'completada',
                                }"
                            >
                                {{ appointment.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            
                            <button
                                v-if="appointment.status === 'pendiente'"
                                @click="accept(appointment.id)"
                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-sm"
                            >
                                Aceptar
                            </button>
                            <button
                                v-if="appointment.status === 'pendiente'"
                                @click="reject(appointment.id)"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm"
                            >
                            Rechazar
                            </button>
                            
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-if="appointments.length === 0" class="text-center py-6 text-gray-500">
                No hay citas registradas.
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
            // initialize selectedDoctor from query param if present
            const urlParams = new URLSearchParams(window.location.search)
            const sel = urlParams.get('doctor_id') || ''
            return { selectedDoctor: sel }
    },
    methods: {
            applyFilter() {
                // visit same index route with doctor_id query param
                const params = {}
                if (this.selectedDoctor) params.doctor_id = this.selectedDoctor
                router.visit(route('appointments.index', params))
            },
        destroy(id) {
            if (confirm('¿Seguro que deseas eliminar esta cita?')) {
                router.delete(route('appointments.destroy', id))
            }
        },
        viewAgenda() {
            if (!this.selectedDoctor) return;
            // Build URL directly to avoid Ziggy missing admin route in some environments
            const url = `/admin/doctor/${this.selectedDoctor}/agenda`
            router.visit(url)
        },
        accept(id) {
            if (!confirm('Aceptar la cita?')) return;
            router.patch(route('admin.appointments.status', id), { status: 'confirmada' })
        },
        reject(id) {
            const reason = prompt('Motivo del rechazo (opcional):')
            const payload = { status: 'rechazada' }
            if (reason) payload.rejection_reason = reason
            router.patch(route('admin.appointments.status', id), payload)
        },
    },
}
</script>
