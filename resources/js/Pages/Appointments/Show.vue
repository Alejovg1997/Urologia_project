<script setup>
import { router } from '@inertiajs/vue3'

const props = defineProps({
    appointment: Object,
})
</script>

<template>
    <div class="p-6 max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Detalles de la Cita Médica</h1>

        <div class="bg-white shadow-md rounded-2xl p-6 space-y-6">
            <!-- Doctor -->
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Doctor</h2>
                <p class="text-gray-900">{{ appointment.doctor ? appointment.doctor.name : '—' }}</p>
                <p class="text-gray-500 text-sm">{{ appointment.doctor ? appointment.doctor.specialty : '' }}</p>
            </div>

            <!-- Información del paciente -->
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Información del paciente</h2>
                <p><strong>Nombre:</strong> {{ appointment.patient_name }}</p>
                <p><strong>Email:</strong> {{ appointment.patient_email }}</p>
                <p><strong>Teléfono:</strong> {{ appointment.patient_phone || 'No registrado' }}</p>
            </div>

            <!-- Fecha y hora -->
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Fecha y hora</h2>
                <p>
                    <strong>Inicio:</strong>
                    {{ new Date(appointment.start_time).toLocaleString() }}
                </p>
                <p>
                    <strong>Fin:</strong>
                    {{ new Date(appointment.end_time).toLocaleString() }}
                </p>
            </div>

            <!-- Estado -->
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Estado</h2>
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
            </div>

            <!-- Motivo -->
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Motivo de la cita</h2>
                <p class="text-gray-800">{{ appointment.reason || 'Sin motivo registrado' }}</p>
            </div>

            <!-- Motivo de rechazo (si aplica) -->
            <div v-if="appointment.rejection_reason">
                <h2 class="text-lg font-semibold text-gray-700 mb-2 text-red-600">
                    Motivo del rechazo
                </h2>
                <p class="text-red-700">{{ appointment.rejection_reason }}</p>
            </div>
        </div>

        <!-- Botones -->
        <div class="flex justify-end mt-6 gap-3">
            <button
                @click="router.visit(route('appointments.index'))"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-4 py-2 rounded-xl"
            >
                Volver
            </button>
            <button
                @click="router.visit(route('appointments.edit', appointment.id))"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-xl"
            >
                Editar
            </button>
        </div>
    </div>
</template>
