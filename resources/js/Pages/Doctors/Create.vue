<template>
  <div class="p-8">
    <h1 class="text-2xl font-bold mb-6">Registrar nuevo doctor</h1>

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
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
          Guardar
        </button>
        <Link :href="route('doctors.index')" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
          Cancelar
        </Link>
      </div>
    </form>
  </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  specialty: 'Urología',
  bio: '',
  phone: '',
  email: ''
})

const submit = () => {
  form.post(route('doctors.store'))
}
</script>
