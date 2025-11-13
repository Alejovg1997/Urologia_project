<template>
  <header class="bg-white border-b p-3 shadow-sm">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
      <div class="flex items-center gap-3">
        <strong class="text-lg">Urologia</strong>
      </div>

      <div class="text-sm text-gray-700">
        <template v-if="user">
          Conectado como <span class="font-medium">{{ user.name ?? user.email }}</span>
          <button @click="logout" class="ml-3 text-red-600">Cerrar sesión</button>
        </template>
        <template v-else>
          <span>No autenticado</span>
          <a :href="route('login')" class="ml-3 text-blue-600">Login</a>
        </template>
      </div>
    </div>
  </header>
</template>

<script setup>
import { usePage, router } from '@inertiajs/vue3'

const page = usePage()

const user = page.props.value?.auth?.user ?? page.props.value?.user ?? null

const logout = () => {
  if (confirm('¿Cerrar sesión?')) {
    router.post(route('logout'))
  }
}
</script>

<style scoped>
header { background: #fff }
</style>
