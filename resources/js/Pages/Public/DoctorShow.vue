<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
const { doctor } = defineProps({ doctor: Object })

const slotsByDay = ref([])
const loading = ref(false)
const error = ref(null)

onMounted(async () => {
  loading.value = true
  try {
    // Build the public availability URL directly to avoid depending on the `route()` helper at runtime
    const url = `/public/doctors/${doctor.slug}/availability`
    const res = await axios.get(url)
    slotsByDay.value = res.data.slots_by_day ?? []
  } catch (e) {
    error.value = 'Error cargando disponibilidades'
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-semibold">Perfil — {{ doctor.name }}</h1>
    <p class="text-sm text-gray-600">{{ doctor.specialty ?? '' }}</p>

    <section class="mt-6">
      <h2 class="font-medium">Próximos espacios</h2>
      <div v-if="loading">Cargando...</div>
      <div v-if="error" class="text-red-500">{{ error }}</div>
      <div v-if="!loading && slotsByDay.length === 0">Sin espacios disponibles</div>
      <div v-for="day in slotsByDay" :key="day.date" class="mt-4">
        <h3 class="font-semibold">{{ day.date }}</h3>
        <div class="flex flex-wrap gap-2 mt-2">
          <button v-for="slot in day.slots" :key="slot.start" class="px-2 py-1 bg-blue-100 rounded text-sm">{{ slot.start }} - {{ slot.end }}</button>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped></style>
