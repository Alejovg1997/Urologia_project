<template>
  <div class="p-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Centro de Urologia de Universidad de Caldas</h1>
    </div>

   

    <div class="grid gap-6 mt-6" style="grid-template-columns:1fr;">
      <div class="panel" style="display:grid;grid-template-columns:1fr;gap:1rem">
        <h2 class="text-xl font-semibold">Panel Público</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;">
          <div class="card" style="padding:1rem;border-radius:6px;background:#eef6ed;text-align:center">
            <div><strong>Agendar una cita</strong></div>
            <div style="margin-top:.6rem;"><Link :href="`/public/availability`" class="button">Reservar</Link></div>
          </div>

          <div class="card" style="padding:1rem;border-radius:6px;background:#eef6ed;text-align:center">
            <div><strong>ver lista de doctores</strong></div>
            <div style="margin-top:.6rem;"><Link :href="`/public/doctors`" class="button">Especialistas</Link></div>
          </div>


          
        </div>
      </div>

      <div class="panel" style="display:grid;grid-template-columns:1fr;gap:1rem;margin-top:1rem">
        <h2 class="text-xl font-semibold">Panel Administrativo</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;">

          <div class="card" style="padding:1rem;border-radius:6px;background:#f1f5f9;text-align:center">
            <div><strong>Gestionar Citas</strong></div>
            <div style="margin-top:.6rem;"><Link :href="`/appointments`" class="button">Ver Citas</Link></div>
          </div>

          <div class="card" style="padding:1rem;border-radius:6px;background:#f1f5f9;text-align:center">
            <div><strong>Agenda doctores</strong></div>
            <div style="margin-top:.6rem;"><Link :href="`/admin/agenda`" class="button">Agenda docotor</Link></div>
          </div>

          <div class="card" style="padding:1rem;border-radius:6px;background:#f1f5f9;text-align:center">
            <div><strong>Gestionar Disponibilidades</strong></div>
            <div style="margin-top:.6rem;"><Link :href="`/availabilities`" class="button">Ver Disponibilidades</Link></div>
          </div>

          <div class="card" style="padding:1rem;border-radius:6px;background:#f1f5f9;text-align:center">
            <div><strong>Gestionar Médicos</strong></div>
            <div style="margin-top:.6rem;"><Link :href="`/doctors`" class="button">Ingreso medico</Link></div>
          </div>

        </div>
      </div>
    </div>

    
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
