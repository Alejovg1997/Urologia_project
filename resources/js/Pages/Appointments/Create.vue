<script setup>
import { useForm, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    doctors: Array,
})

// Formulario con validaciones
const form = useForm({
    doctor_id: '',
    patient_name: '',
    patient_email: '',
    patient_phone: '',
    start_time: '',
    reason: '',
})

const submit = () => {
    form.post(route('appointments.store'), {
        onSuccess: () => {
            form.reset()
        },
    })
}
</script>

<template>
    <div class="p-6 max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Nueva Cita Médica</h1>

        <form @submit.prevent="submit" class="space-y-5 bg-white shadow-md rounded-2xl p-6">
            <!-- Doctor -->
            <div>
                <label class="block font-semibold text-gray-700 mb-2">Doctor</label>
                <select
                    v-model="form.doctor_id"
                    class="w-full border-gray-300 rounded-xl focus:ring focus:ring-blue-200"
                >
                    <option value="" disabled>Seleccione un doctor</option>
                    <option v-for="doctor in doctors" :key="doctor.id" :value="doctor.id">
                        {{ doctor.name }}
                    </option>
                </select>
                <div v-if="form.errors.doctor_id" class="text-red-500 text-sm">
                    {{ form.errors.doctor_id }}
                </div>
            </div>

            <!-- Nombre del paciente -->
            <div>
                <label class="block font-semibold text-gray-700 mb-2">Nombre del paciente</label>
                <input
                    type="text"
                    v-model="form.patient_name"
                    class="w-full border-gray-300 rounded-xl"
                />
                <div v-if="form.errors.patient_name" class="text-red-500 text-sm">
                    {{ form.errors.patient_name }}
                </div>
            </div>

            <!-- Correo del paciente -->
            <div>
                <label class="block font-semibold text-gray-700 mb-2">Correo electrónico</label>
                <input
                    type="email"
                    v-model="form.patient_email"
                    class="w-full border-gray-300 rounded-xl"
                />
                <div v-if="form.errors.patient_email" class="text-red-500 text-sm">
                    {{ form.errors.patient_email }}
                </div>
            </div>

            <!-- Teléfono -->
            <div>
                <label class="block font-semibold text-gray-700 mb-2">Teléfono</label>
                <input
                    type="text"
                    v-model="form.patient_phone"
                    class="w-full border-gray-300 rounded-xl"
                />
                <div v-if="form.errors.patient_phone" class="text-red-500 text-sm">
                    {{ form.errors.patient_phone }}
                </div>
            </div>

            <!-- Fecha y hora -->
            <div>
                <label class="block font-semibold text-gray-700 mb-2">Fecha y hora</label>
                <input
                    type="datetime-local"
                    v-model="form.start_time"
                    class="w-full border-gray-300 rounded-xl"
                />
                <div v-if="form.errors.start_time" class="text-red-500 text-sm">
                    {{ form.errors.start_time }}
                </div>
            </div>

            <!-- Motivo de la cita -->
            <div>
                <label class="block font-semibold text-gray-700 mb-2">Motivo de la cita</label>
                <textarea
                    v-model="form.reason"
                    class="w-full border-gray-300 rounded-xl"
                    rows="3"
                ></textarea>
                <div v-if="form.errors.reason" class="text-red-500 text-sm">
                    {{ form.errors.reason }}
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-3 mt-6">
                <button
                    type="button"
                    @click="router.visit(route('appointments.index'))"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-4 py-2 rounded-xl"
                >
                    Cancelar
                </button>
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-xl"
                    :disabled="form.processing"
                >
                    Guardar Cita
                </button>
            </div>
        </form>
    </div>
</template>
