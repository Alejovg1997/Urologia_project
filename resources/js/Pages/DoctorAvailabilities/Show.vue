<template>
  <div class="p-8 max-w-lg">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold">Detalle Disponibilidad</h1>
      <Link :href="`/availabilities`" class="text-sm text-blue-600">Volver</Link>
    </div>

    <div class="bg-white p-4 rounded shadow">
      <p><strong>Doctor:</strong> {{ availability.doctor ? availability.doctor.name : '—' }}</p>
      <p><strong>Día:</strong> {{ availability.day_of_week }}</p>
      <p><strong>Inicio:</strong> {{ availability.start_time }}</p>
      <p><strong>Fin:</strong> {{ availability.end_time }}</p>
      <div class="mt-4 flex gap-2">
        <Link :href="`/availabilities/${availability.id}/edit`" class="bg-yellow-500 text-white px-3 py-1 rounded">Editar</Link>
        <button @click="remove" class="bg-red-600 text-white px-3 py-1 rounded">Eliminar</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({ availability: Object })

const remove = () => {
  if (confirm('¿Seguro que deseas eliminar esta disponibilidad?')) {
    router.delete(`/availabilities/${props.availability.id}`, {
      onSuccess: () => alert('Eliminada ✅'),
      onError: () => alert('Error ❌'),
    })
  }
}
</script>

<style scoped>
.lead{color:#475569}
</style>
