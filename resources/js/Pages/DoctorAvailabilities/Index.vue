<template>
  <div class="p-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Disponibilidades de Doctores</h1>
      <Link :href="route('availabilities.create')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">+ Nueva</Link>
    </div>

    <div v-if="availabilities.length === 0" class="text-gray-600">No hay disponibilidades registradas.</div>

    <table v-else class="min-w-full bg-white border border-gray-300 shadow-md rounded-lg overflow-hidden">
      <thead class="bg-gray-200">
        <tr>
          <th class="px-4 py-2 text-left">#</th>
          <th class="px-4 py-2 text-left">Doctor</th>
          <th class="px-4 py-2 text-left">Día</th>
          <th class="px-4 py-2 text-left">Inicio</th>
          <th class="px-4 py-2 text-left">Fin</th>
          <th class="px-4 py-2 text-center">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(a, i) in availabilities" :key="a.id" class="border-t hover:bg-gray-50">
          <td class="px-4 py-2">{{ i + 1 }}</td>
          <td class="px-4 py-2">{{ a.doctor ? a.doctor.name : '—' }}</td>
          <td class="px-4 py-2">{{ a.day_of_week }}</td>
          <td class="px-4 py-2">{{ a.start_time }}</td>
          <td class="px-4 py-2">{{ a.end_time }}</td>
          <td class="px-4 py-2 text-center">
            <div class="flex justify-center gap-2">
              <Link :href="route('availabilities.show', a.id)" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">Ver</Link>
              <Link :href="route('availabilities.edit', a.id)" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">Editar</Link>
              <button @click="remove(a.id)" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">Eliminar</button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
  availabilities: Array,
  doctors: Array,
})

const remove = (id) => {
  if (confirm('¿Seguro que deseas eliminar esta disponibilidad?')) {
    router.delete(route('availabilities.destroy', id), {
      onSuccess: () => alert('Disponibilidad eliminada ✅'),
      onError: () => alert('Error al eliminar ❌'),
    })
  }
}
</script>

<style scoped>
table { border-collapse: collapse }
th, td { border: 1px solid #e5e7eb }
</style>
