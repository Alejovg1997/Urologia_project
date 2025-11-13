<template>
  <div class="p-8">
    <h1 class="text-2xl font-bold mb-6">Editar doctor</h1>

    <form @submit.prevent="submit" class="space-y-4 max-w-lg">
      <div>
        <label class="block mb-1 font-semibold">Nombre</label>
        <input v-model="form.name" type="text" class="border rounded p-2 w-full" required />
      </div>

      <div>
        <label class="block mb-1 font-semibold">Especialidad</label>
        <select v-model="form.specialty" class="border rounded p-2 w-full">
          <option value="Urología">Urología</option>
        </select>
      </div>

      <div>
        <label class="block mb-1 font-semibold">Biografía</label>
        <textarea v-model="form.bio" class="border rounded p-2 w-full"></textarea>
      </div>

      <div>
        <label class="block mb-1 font-semibold">Teléfono</label>
        <input v-model="form.phone" type="number" class="border rounded p-2 w-full" />
      </div>

      <div>
        <label class="block mb-1 font-semibold">Correo electrónico</label>
        <input v-model="form.email" type="email" class="border rounded p-2 w-full" required />
      </div>

      <div class="flex gap-3 mt-6">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
          Actualizar
        </button>
        <Link :href="route('doctors.index')" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
          Volver
        </Link>
      </div>
    </form>
  </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'

const props = defineProps({ doctor: Object })

const form = useForm({
  name: props.doctor.name,
  specialty: props.doctor.specialty,
  bio: props.doctor.bio,
  phone: props.doctor.phone,
  email: props.doctor.email
})

const submit = () => {
  form.put(route('doctors.update', props.doctor.slug))
}
</script>
