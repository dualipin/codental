<script setup lang="ts">
import type { OpcionEnfermedad, PacienteExpedienteWizardForm } from '@/types/PacienteExpedienteWizard'

const { form, opcionesEnfermedad } = defineProps<{
  form: PacienteExpedienteWizardForm
  opcionesEnfermedad: OpcionEnfermedad[]
}>()
</script>

<template>
  <section class="space-y-4">
    <h2 class="text-lg font-medium">Paso 2: Historial médico</h2>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
      <label class="form-control md:col-span-2">
        <span class="label-text">Antecedentes hereditarios familiares *</span>
        <input v-model="form.antecedentesHereditarios" type="text" class="input input-bordered w-full" maxlength="255" required>
      </label>
      <label class="form-control">
        <span class="label-text">Alérgico a</span>
        <input v-model="form.alergias" type="text" class="input input-bordered w-full" maxlength="50">
      </label>
      <label class="form-control">
        <span class="label-text">Medicación actual</span>
        <input v-model="form.medicacionActual" type="text" class="input input-bordered w-full" maxlength="50">
      </label>
      <label class="form-control">
        <span class="label-text">Nombre de su médico</span>
        <input v-model="form.nombreMedico" type="text" class="input input-bordered w-full" maxlength="50">
      </label>
      <label class="form-control">
        <span class="label-text">Teléfono del médico</span>
        <input v-model="form.telefonoMedico" type="text" class="input input-bordered w-full" maxlength="10">
      </label>
    </div>

    <div>
      <p class="mb-2 text-sm font-medium">Marque si tiene o ha tenido alguna enfermedad</p>
      <div class="grid grid-cols-1 gap-2 md:grid-cols-3">
        <label
          v-for="disease in opcionesEnfermedad"
          :key="disease.key"
          class="label cursor-pointer justify-start gap-3 rounded-box border border-base-300 px-3 py-2"
        >
          <input v-model="form.enfermedades" type="checkbox" class="checkbox checkbox-primary checkbox-sm" :value="disease.key">
          <span class="label-text">{{ disease.label }}</span>
        </label>
      </div>
    </div>

    <label v-if="form.enfermedades.includes('otras')" class="form-control max-w-xl">
      <span class="label-text">Especifique otras enfermedades</span>
      <input v-model="form.detalleOtrasEnfermedades" type="text" class="input input-bordered" maxlength="30">
    </label>
  </section>
</template>
