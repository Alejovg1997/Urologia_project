<script setup>
import { router } from '@inertiajs/vue3'
import { reactive, computed } from 'vue'

const props = defineProps({
    doctors: Array,
    doctors_list: Array,
    filters: Object,
})

const filters = reactive({
    start_date: props.filters?.start_date || '',
    end_date: props.filters?.end_date || '',
    doctor_id: props.filters?.doctor_id || '',
})

const doctorsForSelect = computed(() => {
    if (props.doctors_list && props.doctors_list.length) return props.doctors_list
    // fallback: build list from doctors prop
    if (props.doctors && props.doctors.length) return props.doctors.map(d => ({ id: d.id, name: d.name, specialty: d.specialty }))
    return []
})

function fmt(dt) {
    return new Date(dt).toLocaleString()
}

function applyFilter() {
    const params = {}
    if (filters.start_date) params.start_date = filters.start_date
    if (filters.end_date) params.end_date = filters.end_date
    if (filters.doctor_id) params.doctor_id = filters.doctor_id
    router.visit(route('admin.agenda.all') + '?' + new URLSearchParams(params).toString())
}
</script>

<template>
    <div class="p-8">
        <h1 class="text-2xl font-bold mb-6">Agenda global — Citas confirmadas por médico</h1>

        <div class="mb-4 p-4 bg-white rounded shadow">
            <div class="flex items-center gap-3">
                <label class="text-sm">Médico:</label>
                <select v-model="filters.doctor_id" class="border rounded px-3 py-2">
                    <option value="">Todos</option>
                    <option v-for="d in doctorsForSelect" :key="d.id" :value="d.id">{{ d.name }} — {{ d.specialty }}</option>
                </select>
                <label class="text-sm">Desde:</label>
                <input type="date" v-model="filters.start_date" class="border rounded px-2 py-1" />
                <label class="text-sm">Hasta:</label>
                <input type="date" v-model="filters.end_date" class="border rounded px-2 py-1" />
                <button @click.prevent="applyFilter" class="ml-2 px-3 py-1 bg-blue-600 text-white rounded">Filtrar</button>
            </div>
        </div>

        <div v-if="doctors.length === 0" class="text-gray-500">No hay médicos registrados.</div>

        <div v-for="doc in doctors" :key="doc.id" class="mb-8 bg-white rounded shadow p-4">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-semibold">{{ doc.name }}</h2>
                    <div class="text-sm text-gray-600">Especialidad: {{ doc.specialty || '—' }}</div>
                </div>
                
            </div>

            <table class="w-full table-auto">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="px-4 py-2">Fecha</th>
                        <th class="px-4 py-2">Hora inicio</th>
                        <th class="px-4 py-2">Paciente</th>
                        <th class="px-4 py-2">Especialidad</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="ap in doc.appointments" :key="ap.id" class="border-b">
                        <td class="px-4 py-2">{{ new Date(ap.start_time).toLocaleDateString() }}</td>
                        <td class="px-4 py-2">{{ new Date(ap.start_time).toLocaleTimeString() }}</td>
                        <td class="px-4 py-2">{{ ap.patient_name }}</td>
                        <td class="px-4 py-2"><span class="px-3 py-1 rounded-full bg-blue-100 text-blue-800">{{ doc.specialty || '—' }}</span></td>
                    </tr>
                    <tr v-if="!doc.appointments || doc.appointments.length === 0">
                        <td colspan="4" class="px-4 py-4 text-gray-500">Sin citas confirmadas.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

