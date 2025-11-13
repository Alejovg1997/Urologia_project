<script setup>
const props = defineProps({
    doctor: Object,
    week_start: String,
    week_end: String,
    appointments: Array,
})

function formatDate(dt) {
    return new Date(dt).toLocaleString()
}
</script>

<template>
    <div class="p-8">
        <h1 class="text-2xl font-bold mb-4">Agenda semanal — {{ doctor.name }}</h1>
        <div class="mb-4 text-sm text-gray-600">Semana: {{ week_start }} → {{ week_end }}</div>

        <div class="bg-white rounded shadow p-4">
            <table class="w-full table-auto">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="px-4 py-2">Día</th>
                        <th class="px-4 py-2">Hora inicio</th>
                        <th class="px-4 py-2">Paciente</th>
                        <th class="px-4 py-2">Estado</th>
                        <th class="px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="ap in appointments" :key="ap.id" class="border-b">
                        <td class="px-4 py-2">{{ new Date(ap.start_time).toLocaleDateString() }}</td>
                        <td class="px-4 py-2">{{ new Date(ap.start_time).toLocaleTimeString() }}</td>
                        <td class="px-4 py-2">{{ ap.patient_name }}</td>
                        <td class="px-4 py-2">{{ ap.status }}</td>
                        <td class="px-4 py-2">
                            <a :href="route('appointments.show', ap.id)" class="text-indigo-600">Ver</a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div v-if="appointments.length === 0" class="p-4 text-gray-500">No hay citas esta semana.</div>
        </div>
    </div>
</template>
