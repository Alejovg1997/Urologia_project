<template>
  <div class="p-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Listado de Doctores</h1>

      <Link
        :href="`/doctors/create`"
        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow"
      >
        + Nuevo Doctor
      </Link>
    </div>

    <div v-if="doctors.length === 0" class="text-gray-600">
      No hay doctores registrados aún.
    </div>

    <table v-else class="min-w-full bg-white border border-gray-300 shadow-md rounded-lg overflow-hidden">
      <thead class="bg-gray-200">
        <tr>
          <th class="px-4 py-2 text-left">#</th>
          <th class="px-4 py-2 text-left">Nombre</th>
          <th class="px-4 py-2 text-left">Especialidad</th>
          <th class="px-4 py-2 text-left">Teléfono</th>
          <th class="px-4 py-2 text-left">Email</th>
          <th class="px-4 py-2 text-center">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(doctor, index) in doctors"
          :key="doctor.id"
          class="border-t hover:bg-gray-50"
        >
          <td class="px-4 py-2">{{ index + 1 }}</td>
          <td class="px-4 py-2">{{ doctor.name }}</td>
          <td class="px-4 py-2">{{ doctor.specialty }}</td>
          <td class="px-4 py-2">{{ doctor.phone }}</td>
          <td class="px-4 py-2">{{ doctor.email }}</td>
          <td class="px-4 py-2 text-center">
            <div class="flex justify-center gap-2">
              <Link
                :href="`/doctors/${doctor.slug}`"
                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded"
              >
                Ver
              </Link>

              <Link
                :href="`/doctors/${doctor.slug}/edit`"
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded"
              >
                Editar
              </Link>

              <button
                @click="deleteDoctor(doctor.slug)"
                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded"
              >
                Eliminar
              </button>
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
  doctors: Array
})

const deleteDoctor = (slug) => {
  if (confirm('¿Seguro que deseas eliminar este doctor?')) {
    // Use explicit URL to avoid relying on route() in the script runtime
    router.delete(`/doctors/${slug}`, {
      onSuccess: () => {
        alert('Doctor eliminado correctamente ✅')
      },
      onError: () => {
        alert('❌ Error al eliminar el doctor')
      },
    })
  }
}
</script>

<style scoped>
table {
  border-collapse: collapse;
}
th,
td {
  border: 1px solid #e5e7eb;
}
</style>
