<template>
  <div class="p-6">
    <h1 class="text-2xl font-semibold mb-4">Doctores — Público</h1>

    <div v-if="doctors.length === 0" class="text-gray-600">No hay doctores disponibles.</div>

    <div class="grid gap-4" style="grid-template-columns:repeat(auto-fit,minmax(240px,1fr));">
      <div v-for="doc in doctors" :key="doc.id" class="bg-white p-4 rounded shadow">
        <h2 class="font-semibold">{{ doc.name }}</h2>
        <p class="text-sm text-gray-600">{{ doc.specialty }}</p>
        <p class="mt-2 text-gray-700 text-sm">{{ doc.bio || 'Sin biografía' }}</p>
        <div class="mt-4">
          <Link :href="`/doctors/${doc.slug}`" class="bg-blue-500 text-white px-3 py-1 rounded">Ver disponibilidad</Link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  doctors: Array
})
</script>

<style scoped>
</style>
