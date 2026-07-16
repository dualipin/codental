<script setup lang="ts">
import type { RegistrarPacienteWizardForm } from '@/types/RegistrarPacienteWizard'

const { form } = defineProps<{
  form: RegistrarPacienteWizardForm
  formularioPublico: boolean
}>()
</script>

<template>
  <section class="space-y-4">
    <h2 class="text-lg font-medium">Paso 4: Historial Odontológico</h2>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
      <label class="form-control">
        <span class="label-text">Última revisión odontológica</span>
        <input v-model="form.ultimaRevisionDental" type="text" class="input input-bordered w-full" maxlength="10" placeholder="Ej. Hace 6 meses" />
      </label>
      <label class="form-control">
        <span class="label-text">Motivo de la última visita dental</span>
        <input v-model="form.motivoUltimaVisitaDental" type="text" class="input input-bordered w-full" maxlength="50" placeholder="Ej. Limpieza, dolor" />
      </label>

      <label class="form-control">
        <span class="label-text">Utiliza auxiliares de limpieza bucal</span>
        <select v-model="form.usaAuxiliaresLimpiezaBucal" class="select select-bordered w-full">
          <option :value="false">No</option>
          <option :value="true">Si</option>
        </select>
      </label>
      <label v-if="form.usaAuxiliaresLimpiezaBucal" class="form-control">
        <span class="label-text">Cuales</span>
        <input v-model="form.detalleAuxiliaresLimpiezaBucal" type="text" class="input input-bordered w-full" maxlength="50" placeholder="Ej. Hilo dental, enjuague" />
      </label>

      <label class="form-control md:col-span-2">
        <span class="label-text">Frecuencia y tiempo de cepillado</span>
        <input v-model="form.frecuenciaCepillado" type="text" class="input input-bordered w-full" maxlength="25" placeholder="Ej. 3 veces al día, 2 min" />
      </label>

      <label class="form-control">
        <span class="label-text">Infiltración de anestesia local</span>
        <select v-model="form.recibioAnestesiaLocal" class="select select-bordered w-full">
          <option :value="false">No</option>
          <option :value="true">Si</option>
        </select>
      </label>
      <label v-if="form.recibioAnestesiaLocal" class="form-control">
        <span class="label-text">Complicaciones con anestesia</span>
        <select v-model="form.complicacionAnestesia" class="select select-bordered w-full">
          <option :value="false">No</option>
          <option :value="true">Si</option>
        </select>
      </label>
      <label v-if="form.recibioAnestesiaLocal && form.complicacionAnestesia" class="form-control md:col-span-2">
        <span class="label-text">Describa complicación</span>
        <input v-model="form.detalleComplicacionAnestesia" type="text" class="input input-bordered w-full" maxlength="50" placeholder="Ej. Hinchazón, dolor" />
      </label>

      <label class="form-control">
        <span class="label-text">Uso de remedio casero o naturista</span>
        <select v-model="form.usoRemedioCasero" class="select select-bordered w-full">
          <option :value="false">No</option>
          <option :value="true">Si</option>
        </select>
      </label>
      <label v-if="form.usoRemedioCasero" class="form-control">
        <span class="label-text">Describa remedio utilizado</span>
        <input v-model="form.detalleRemedioCasero" type="text" class="input input-bordered w-full" maxlength="30" placeholder="Ej. Enjuague de sal" />
      </label>

      <label class="form-control">
        <span class="label-text">Dolor o dificultad al masticar</span>
        <select v-model="form.dolorAlMasticar" class="select select-bordered w-full">
          <option :value="false">No</option>
          <option :value="true">Si</option>
        </select>
      </label>
      <label v-if="form.dolorAlMasticar" class="form-control">
        <span class="label-text">Describa dolor al masticar</span>
        <input v-model="form.detalleDolorAlMasticar" type="text" class="input input-bordered w-full" maxlength="20" placeholder="Ej. Lado izquierdo" />
      </label>

      <label class="form-control">
        <span class="label-text">Sangrado o inflamación</span>
        <select v-model="form.sangradoOInflamacion" class="select select-bordered w-full">
          <option :value="false">No</option>
          <option :value="true">Si</option>
        </select>
      </label>
      <label v-if="form.sangradoOInflamacion" class="form-control">
        <span class="label-text">Describa sangrado o inflamación</span>
        <input v-model="form.detalleSangradoOInflamacion" type="text" class="input input-bordered w-full" maxlength="20" placeholder="Ej. Al cepillarme" />
      </label>

      <label class="form-control">
        <span class="label-text">Padece úlceras bucales o fuegos</span>
        <select v-model="form.ulcerasOrales" class="select select-bordered w-full">
          <option :value="false">No</option>
          <option :value="true">Si</option>
        </select>
      </label>
      <label v-if="form.ulcerasOrales" class="form-control">
        <span class="label-text">Frecuencia</span>
        <input v-model="form.frecuenciaUlcerasOrales" type="text" class="input input-bordered w-full" maxlength="20" placeholder="Ej. Cada mes" />
      </label>

      <label class="form-control">
        <span class="label-text">Tiene hábitos bucales</span>
        <select v-model="form.habitosOrales" class="select select-bordered w-full">
          <option :value="false">No</option>
          <option :value="true">Si</option>
        </select>
      </label>
      <label v-if="form.habitosOrales" class="form-control md:col-span-2">
        <span class="label-text">Cuales hábitos</span>
        <input v-model="form.detalleHabitosOrales" type="text" class="input input-bordered w-full" maxlength="100" placeholder="Ej. Bruxismo, morderse uñas" />
      </label>
    </div>
  </section>
</template>
