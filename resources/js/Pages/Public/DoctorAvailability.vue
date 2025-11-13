<script setup>
import { ref, watch, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps({
    doctors: Array,
})

const selectedDoctor = ref(props.doctors.length ? props.doctors[0].slug : null)
const weekStart = ref(new Date())
const slotsByDay = ref([])
const loading = ref(false)
const message = ref(null)
const errors = ref({})

function toISODate(d) {
    const dt = new Date(d)
    dt.setHours(0,0,0,0)
    return dt.toISOString().slice(0,10)
}

function mondayOf(date) {
    const d = new Date(date)
    const day = d.getDay() || 7 // sunday -> 7
    if (day !== 1) d.setDate(d.getDate() - (day - 1))
    d.setHours(0,0,0,0)
    return d
}

function loadSlots() {
    if (!selectedDoctor.value) return
    loading.value = true
    errors.value = {}
    message.value = null

    const start = toISODate(mondayOf(weekStart.value))
    axios.get(route('public.doctor.availability', selectedDoctor.value), { params: { start } })
        .then(res => {
            slotsByDay.value = res.data.slots_by_day || []
            message.value = null
        })
        .catch(err => {
            console.error('Error fetching availability', err)
            // Try to show a helpful message from the server if present
            if (err.response && err.response.data) {
                // If Laravel returned JSON with message or error
                const data = err.response.data
                message.value = data.message || (data.error || JSON.stringify(data))
            } else {
                message.value = err.message || 'Error cargando disponibilidades.'
            }
        })
        .finally(() => loading.value = false)
}

watch(selectedDoctor, loadSlots)

onMounted(() => {
    weekStart.value = mondayOf(new Date())
    loadSlots()
})

// booking form state
const bookingOpen = ref(false)
const bookingSlot = ref(null)
const form = ref({ patient_name: '', patient_email: '', patient_phone: '', reason: '' })

function openBooking(slot) {
    bookingSlot.value = slot
    bookingOpen.value = true
}

function submitBooking() {
    errors.value = {}
    message.value = null
    // selectedDoctor holds the doctor's slug for route binding; map to numeric id for payload
    const selectedDoc = props.doctors.find(d => d.slug === selectedDoctor.value)
    const payload = {
        doctor_id: selectedDoc ? selectedDoc.id : selectedDoctor.value,
        start_time: bookingSlot.value.start,
        ...form.value
    }

    axios.post(route('public.appointments.store'), payload)
        .then(res => {
            message.value = res.data.message || 'Solicitud enviada.'
            bookingOpen.value = false
            form.value = { patient_name: '', patient_email: '', patient_phone: '', reason: '' }
            loadSlots()
        })
        .catch(err => {
            if (err.response && err.response.status === 422) {
                errors.value = err.response.data.errors || { general: err.response.data.message }
            } else {
                message.value = 'Error enviando la solicitud.'
            }
        })
}
</script>

<template>
    <div class="p-6 max-w-5xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Disponibilidad semanal por médico</h1>

        <div class="flex gap-4 mb-4">
            <select v-model="selectedDoctor" class="border rounded px-3 py-2">
                <option v-for="d in doctors" :key="d.id" :value="d.id">{{ d.name }} — {{ d.specialty }}</option>
            </select>

            <button class="px-3 py-2 bg-gray-200 rounded" @click.prevent="weekStart = new Date(new Date(weekStart).setDate(new Date(weekStart).getDate()-7)); loadSlots()">Semana anterior</button>
            <button class="px-3 py-2 bg-gray-200 rounded" @click.prevent="weekStart = new Date(new Date(weekStart).setDate(new Date(weekStart).getDate()+7)); loadSlots()">Semana siguiente</button>
        </div>

        <div v-if="loading">Cargando...</div>

        <div v-if="message" class="mb-4 text-green-700">{{ message }}</div>

        <div v-if="!loading" class="grid grid-cols-7 gap-4">
            <div v-for="day in slotsByDay" :key="day.date" class="bg-white p-3 rounded shadow">
                <h3 class="font-semibold mb-2">{{ day.date }}</h3>
                <div v-if="day.slots.length === 0" class="text-sm text-gray-500">Sin espacios disponibles</div>
                <ul>
                    <li v-for="slot in day.slots" :key="slot.start" class="mb-2">
                        <button class="w-full text-left px-2 py-1 border rounded hover:bg-gray-50" @click.prevent="openBooking(slot)">
                            {{ new Date(slot.start).toLocaleString() }} — {{ new Date(slot.end).toLocaleTimeString() }}
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Booking modal/simple panel -->
        <div v-if="bookingOpen" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">
            <div class="bg-white rounded p-6 w-96">
                <h2 class="text-lg font-semibold mb-3">Reservar cita</h2>
                <p class="mb-2">Horario: {{ new Date(bookingSlot.start).toLocaleString() }}</p>

                <label class="block mb-2">
                    <span class="text-sm">Nombre</span>
                    <input v-model="form.patient_name" class="w-full border px-2 py-1 rounded" />
                </label>

                <label class="block mb-2">
                    <span class="text-sm">Email</span>
                    <input v-model="form.patient_email" class="w-full border px-2 py-1 rounded" />
                </label>

                <label class="block mb-2">
                    <span class="text-sm">Teléfono</span>
                    <input v-model="form.patient_phone" class="w-full border px-2 py-1 rounded" />
                </label>

                <label class="block mb-4">
                    <span class="text-sm">Motivo (opcional)</span>
                    <textarea v-model="form.reason" class="w-full border px-2 py-1 rounded"></textarea>
                </label>

                <div class="flex justify-end gap-2">
                    <button @click.prevent="bookingOpen = false" class="px-3 py-2 rounded bg-gray-200">Cancelar</button>
                    <button @click.prevent="submitBooking" class="px-3 py-2 rounded bg-blue-600 text-white">Enviar</button>
                </div>

                <div v-if="Object.keys(errors).length" class="mt-3 text-sm text-red-600">
                    <div v-for="(err, k) in errors" :key="k">{{ Array.isArray(err) ? err.join(', ') : err }}</div>
                </div>
            </div>
        </div>
    </div>
</template>
