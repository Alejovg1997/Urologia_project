<template>
  <div class="p-8 max-w-lg">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold">Editar Disponibilidad</h1>
      <Link :href="`/availabilities`" class="text-sm text-blue-600">Volver</Link>
    </div>

    <form @submit.prevent="submit">
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Doctor</label>
        <select v-model="form.doctor_id" class="w-full border rounded p-2">
          <option value="">-- Selecciona --</option>
          <option v-for="d in doctors" :key="d.id" :value="d.id">{{ d.name }}</option>
        </select>
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Día de la semana</label>
        <select v-model="form.day_of_week" class="w-full border rounded p-2">
          <option value="">-- Selecciona día --</option>
          <option v-for="d in days" :key="d" :value="d">{{ d }}</option>
        </select>
      </div>

      <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
          <label class="block text-sm font-medium mb-1">Inicio</label>
          <input type="time" v-model="form.start_time" class="w-full border rounded p-2" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Fin</label>
          <input type="time" v-model="form.end_time" class="w-full border rounded p-2" />
        </div>
      </div>

      <div class="flex gap-2">
        <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded">Actualizar</button>
        <Link :href="`/availabilities`" class="px-4 py-2 border rounded">Cancelar</Link>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({ availability: Object, doctors: Array })


const doctors = props.doctors || []

const days = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo']

const form = ref({
  doctor_id: props.availability.doctor_id || '',
  day_of_week: props.availability.day_of_week || '',
  start_time: props.availability.start_time || '',
  end_time: props.availability.end_time || '',
})

const submit = () => {
  router.put(`/availabilities/${props.availability.id}`, form.value, {
    onSuccess: () => alert('Disponibilidad actualizada ✅'),
    onError: () => alert('Error al actualizar ❌'),
  })
}
</script>

<style scoped>
.lead{color:#475569}
</style>
