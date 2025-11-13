<script setup>
import { ref } from 'vue'
import axios from 'axios'
const props = defineProps({ doctor: Object, start: String })

const form = ref({
  doctor_id: props.doctor ? props.doctor.id : null,
  patient_name: '',
  patient_email: '',
  patient_phone: '',
  start_time: props.start || '',
  reason: '',
})

const submitting = ref(false)
const message = ref(null)

async function submit() {
  submitting.value = true
  message.value = null
  try {
    const res = await axios.post(route('public.appointments.store'), form.value)
    message.value = res.data.message || 'Solicitud enviada.'
  } catch (e) {
    message.value = e.response?.data?.message || 'Error al enviar.'
  } finally {
    submitting.value = false
  }
}
</script>

<template>
  <div class="p-6 max-w-lg">
    <h1 class="text-xl font-semibold">Reservar cita</h1>
    <div class="mt-3 text-sm text-gray-700">
      <div v-if="doctor">Médico: {{ doctor.name }}</div>
      <div v-if="form.start_time">Inicio: {{ form.start_time }}</div>
    </div>

    <div class="mt-4">
      <label class="block text-sm">Nombre</label>
      <input v-model="form.patient_name" class="w-full border p-2 rounded" />
    </div>
    <div class="mt-2">
      <label class="block text-sm">Email</label>
      <input v-model="form.patient_email" class="w-full border p-2 rounded" />
    </div>
    <div class="mt-2">
      <label class="block text-sm">Teléfono</label>
      <input v-model="form.patient_phone" class="w-full border p-2 rounded" />
    </div>
    <div class="mt-2">
      <label class="block text-sm">Motivo (opcional)</label>
      <textarea v-model="form.reason" class="w-full border p-2 rounded"></textarea>
    </div>

    <div class="mt-4">
      <button @click.prevent="submit" :disabled="submitting" class="bg-green-600 text-white px-4 py-2 rounded">Enviar</button>
    </div>

    <div v-if="message" class="mt-3 text-sm text-gray-700">{{ message }}</div>
  </div>
</template>

<style scoped></style>
