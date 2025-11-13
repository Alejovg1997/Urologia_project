<template>
  <div class="p-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Urologia — Home</h1>
    </div>

    <p class="lead text-gray-600">Navega rápidamente a las rutas públicas y a las secciones principales de la app (Inertia + Vue).</p>

    <div class="grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-top:1rem">
      <div class="card" style="padding:1rem;border-radius:6px;background:#f1f5f9;text-align:center">
        <div><strong>Inicio</strong></div>
        <div style="margin-top:.6rem;"><Link href="/" class="button">Home (esta página)</Link></div>
      </div>

      <div class="card" style="padding:1rem;border-radius:6px;background:#f1f5f9;text-align:center">
        <div><strong>Bienvenida (raíz)</strong></div>
        <div style="margin-top:.6rem;"><Link :href="route('dashboard')" class="button">Dashboard</Link></div>
      </div>

      <div class="card" style="padding:1rem;border-radius:6px;background:#f1f5f9;text-align:center">
        <div><strong>Médicos</strong></div>
        <div style="margin-top:.6rem;"><Link :href="route('doctors.index')" class="button">Listado de médicos</Link></div>
      </div>

      <div class="card" style="padding:1rem;border-radius:6px;background:#f1f5f9;text-align:center">
        <div><strong>Citas</strong></div>
        <div style="margin-top:.6rem;"><Link :href="route('appointments.index')" class="button">Citas</Link></div>
      </div>

      <div class="card" style="padding:1rem;border-radius:6px;background:#eef2ff;text-align:center">
        <div><strong>Disponibilidad pública</strong></div>
        <div style="margin-top:.6rem;"><Link :href="route('public.availability.index')" class="button">Consultar / Reservar</Link></div>
      </div>

      <div class="card" style="padding:1rem;border-radius:6px;background:#eef6ed;text-align:center">
        <div><strong>Perfil público (ejemplo)</strong></div>
        <div style="margin-top:.6rem;"><Link :href="route('public.doctors.show', { doctor: 'maria-gomez' })" class="button">Ver perfil (ejemplo)</Link></div>
      </div>

      <div class="card" style="padding:1rem;border-radius:6px;background:#fff7ed;text-align:center">
        <div><strong>Formulario de reserva</strong></div>
        <div style="margin-top:.6rem;"><Link :href="route('public.appointments.create')" class="button">Ir al formulario</Link></div>
      </div>

      <div class="card" style="padding:1rem;border-radius:6px;background:#f1f5f9;text-align:center">
        <div><strong>Disponibilidades</strong></div>
        <div style="margin-top:.6rem;"><Link :href="route('availabilities.index')" class="button">Ver disponibilidades</Link></div>
      </div>

      <div class="card" style="padding:1rem;border-radius:6px;background:#f1f5f9;text-align:center">
        <div><strong>Autenticación</strong></div>
        <div style="margin-top:.6rem;display:flex;gap:.5rem;justify-content:center">
          <Link v-if="canLogin" :href="route('login')" class="button">Login</Link>
          <Link v-if="canRegister" :href="route('register')" class="button">Registro</Link>
        </div>
      </div>

      <div class="card" style="padding:1rem;border-radius:6px;background:#f8fafc;text-align:center">
        <div><strong>Panel Admin</strong></div>
        <div style="margin-top:.6rem;display:flex;gap:.5rem;justify-content:center">
          <Link :href="route('appointments.index')" class="button">Gestionar Citas</Link>
          <Link :href="route('availabilities.index')" class="button">Gestionar Disponibilidades</Link>
          <Link :href="route('doctors.index')" class="button">Gestionar Médicos</Link>
        </div>
      </div>

    </div>

    <footer style="margin-top:1.25rem;color:#64748b;font-size:.9rem">
      Laravel: {{ laravelVersion }} — PHP: {{ phpVersion }}
    </footer>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  laravelVersion: String,
  phpVersion: String,
})
</script>

<style scoped>
.button{display:inline-block;padding:.5rem .75rem;border-radius:6px;background:#0ea5e9;color:#fff;text-decoration:none;font-weight:600}
.lead{margin-top:.5rem}
</style>
