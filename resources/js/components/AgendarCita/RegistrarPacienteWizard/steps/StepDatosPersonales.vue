<script setup lang="ts">
import type { RegistrarPacienteWizardForm } from '@/types/RegistrarPacienteWizard'

const { form } = defineProps<{
  form: RegistrarPacienteWizardForm
  formularioPublico: boolean
}>()
</script>

<template>
  <section class="space-y-4">
    <h2 class="text-lg font-medium">Paso 1: Datos Personales</h2>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
      <label class="form-control">
        <span class="label-text">Nombre(s) *</span>
        <input v-model="form.nombre" type="text" class="input input-bordered w-full" maxlength="255" required placeholder="Ej. Juan"/>
      </label>
      <label class="form-control">
        <span class="label-text">Apellido paterno *</span>
        <input v-model="form.apellidoPaterno" type="text" class="input input-bordered w-full" maxlength="255"
               required placeholder="Ej. Pérez"/>
      </label>
      <label class="form-control">
        <span class="label-text">Apellido materno</span>
        <input v-model="form.apellidoMaterno" type="text" class="input input-bordered w-full" maxlength="255" placeholder="Ej. López"/>
      </label>

      <label class="form-control">
        <span class="label-text">Fecha de nacimiento *</span>
        <input v-model="form.fechaNacimiento" type="date" class="input input-bordered w-full" required/>
      </label>
      <label class="form-control">
        <span class="label-text">Sexo *</span>
        <select v-model="form.sexo" class="select select-bordered w-full" required>
          <option value="M">Masculino</option>
          <option value="F">Femenino</option>
          <option value="O">Prefiero no especificar</option>
        </select>
      </label>
      <label class="form-control">
        <span class="label-text">Ocupación</span>
        <input v-model="form.ocupacion" type="text" class="input input-bordered w-full" maxlength="10" placeholder="Ej. Ingeniero"/>
      </label>

      <label class="form-control">
        <span class="label-text">Estado civil</span>
        <input v-model="form.estadoCivil" type="text" class="input input-bordered w-full" maxlength="15" placeholder="Ej. Soltero/a"/>
      </label>
      <label class="form-control">
        <span class="label-text">Teléfono *</span>
        <input v-model="form.telefono" type="tel" class="input input-bordered w-full" maxlength="10"
               pattern="[0-9]{10}" required placeholder="Ej. 5512345678"/>
      </label>
      <label class="form-control">
        <span class="label-text">Correo electrónico</span>
        <input v-model="form.correo" type="email" class="input input-bordered w-full" maxlength="255" placeholder="Ej. correo@ejemplo.com"/>
      </label>

      <label class="form-control">
        <span class="label-text">Estado</span>
        <input v-model="form.estado" type="text" class="input input-bordered w-full" maxlength="255" placeholder="Ej. CDMX"/>
      </label>
      <label class="form-control">
        <span class="label-text">Municipio</span>
        <input v-model="form.municipio" type="text" class="input input-bordered w-full" maxlength="255" placeholder="Ej. Benito Juárez"/>
      </label>
      <label class="form-control md:col-span-3">
        <span class="label-text">Dirección completa</span>
        <input v-model="form.direccion" type="text" class="input input-bordered w-full" maxlength="255" placeholder="Ej. Calle, número, colonia"/>
      </label>

      <label class="form-control">
        <span class="label-text">Religión</span>
        <input v-model="form.religion" type="text" class="input input-bordered w-full" maxlength="255" placeholder="Ej. Católica"/>
      </label>
    </div>
  </section>
</template>
