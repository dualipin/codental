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
        <select v-model="form.ocupacion" class="select select-bordered w-full">
          <option value="" disabled>Seleccione una opción</option>
          <option value="Empleado(a)">Empleado(a)</option>
          <option value="Profesionista">Profesionista</option>
          <option value="Comerciante">Comerciante</option>
          <option value="Estudiante">Estudiante</option>
          <option value="Jubilado(a)">Jubilado(a)</option>
          <option value="Hogar">Hogar</option>
          <option value="Desempleado(a)">Desempleado(a)</option>
        </select>
      </label>

      <label class="form-control">
        <span class="label-text">Estado civil</span>
        <select v-model="form.estadoCivil" class="select select-bordered w-full">
          <option value="">Seleccione una opción</option>
          <option value="Soltero(a)">Soltero(a)</option>
          <option value="Casado(a)">Casado(a)</option>
          <option value="Divorciado(a)">Divorciado(a)</option>
          <option value="Viudo(a)">Viudo(a)</option>
        </select>
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
        <select v-model="form.religion" class="select select-bordered w-full">
          <option value="" disabled>Seleccione una opción</option>
          <option value="Católica">Católica</option>
          <option value="Cristiana">Cristiana</option>
          <option value="Evangélica">Evangélica</option>
          <option value="Testigo de Jehová">Testigo de Jehová</option>
          <option value="Mormona">Mormona</option>
          <option value="Otra">Otra</option>
          <option value="Ninguna">Ninguna</option>
        </select>
      </label>
    </div>
  </section>
</template>
