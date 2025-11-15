<template>
  <div class="p-8">
    <h1 class="text-2xl font-bold mb-6">Detalles del doctor</h1>

    <div class="bg-white shadow p-6 rounded-lg w-full max-w-lg">
      <p><strong>Nombre:</strong> {{ doctor.name }}</p>
      <p><strong>Especialidad:</strong> {{ doctor.specialty }}</p>
      <p><strong>Biografía:</strong> {{ doctor.bio || 'Sin biografía' }}</p>
      <p><strong>Teléfono:</strong> {{ doctor.phone }}</p>
      <p><strong>Email:</strong> {{ doctor.email }}</p>
    </div>

    <div class="flex gap-3 mt-6">
      <Link :href="`/doctors/${doctor.slug}/edit`" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
        Editar
      </Link>
      <Link :href="`/doctors`" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
        Volver
      </Link>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  doctor: Object
})
</script>
