<script setup lang="ts">
import type { Doctor } from '@/types/Doctor'
import type { RegistrarPacienteWizardForm } from '@/types/RegistrarPacienteWizard'

const { form, doctores, doctorSeleccionado } = defineProps<{
  form: RegistrarPacienteWizardForm
  doctores: Doctor[]
  doctorSeleccionado: Doctor | null
}>()

</script>

<template>
  <section class="space-y-4">
    <h2 class="text-lg font-medium">Paso 5: Asignación</h2>

    <label class="form-control max-w-xl">
      <span class="label-text">Selecciona el dentista *</span>
      <select v-model="form.usuarioAsignado" class="select select-bordered w-full" required>
        <option value="">Seleccione un medico</option>
        <option v-for="doctor in doctores" :key="String(doctor.id)" :value="String(doctor.id)">
          Dr(a). {{ doctor.nombre }} {{ doctor.apellido_paterno }}
        </option>
      </select>
      <span class="label-text-alt text-base-content/70">Primero elige un dentista para guardar el expediente y continuar a la agenda.</span>
    </label>

    <div v-if="doctorSeleccionado" class="card border border-base-300 bg-base-100 max-w-xl">
      <div class="card-body flex-row items-center gap-4">
        <div class="avatar">
          <div class="w-16 h-16 rounded-full bg-base-300 flex items-center justify-center overflow-hidden">
            <img v-if="doctorSeleccionado?.foto_usuario" :src="doctorSeleccionado.foto_usuario"
                 :alt="`Dr(a). ${doctorSeleccionado.nombre} ${doctorSeleccionado.apellido_paterno}`"
                 class="w-full h-full object-cover"/>
            <i v-else class="bi bi-person text-4xl"></i>
          </div>
        </div>
        <div>
          <p class="font-semibold">Dr(a). {{ doctorSeleccionado.nombre }} {{
              doctorSeleccionado.apellido_paterno
            }}</p>
          <p class="text-sm text-base-content/70">Especialidad:
            {{ doctorSeleccionado.especialidad || 'Sin especialidad registrada' }}</p>
        </div>
      </div>
    </div>
  </section>
</template>
