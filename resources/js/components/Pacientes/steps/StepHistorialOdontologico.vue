<script setup lang="ts">
import type { PacienteExpedienteWizardForm } from '@/types/PacienteExpedienteWizard'

const { form } = defineProps<{
  form: PacienteExpedienteWizardForm
}>()
</script>

<template>
  <section class="space-y-4">
    <h2 class="text-lg font-medium">Paso 4: Historial odontológico</h2>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
      <label class="form-control">
        <span class="label-text">Última revisión odontológica</span>
        <select v-model="form.ultimaRevisionDental" class="select select-bordered w-full">
          <option value="" disabled>Seleccione una opción</option>
          <option value="Menos de 6 meses">Menos de 6 meses</option>
          <option value="6 a 12 meses">6 a 12 meses</option>
          <option value="1 a 2 años">1 a 2 años</option>
          <option value="Más de 2 años">Más de 2 años</option>
          <option value="Nunca">Nunca</option>
        </select>
      </label>
      <label class="form-control">
        <span class="label-text">Motivo de la última visita dental</span>
        <input v-model="form.motivoUltimaVisitaDental" type="text" class="input input-bordered w-full" maxlength="50">
      </label>

      <label class="form-control">
        <span class="label-text">Utiliza auxiliares de limpieza bucal</span>
        <select v-model="form.usaAuxiliaresLimpiezaBucal" class="select select-bordered w-full">
          <option :value="false">No</option>
          <option :value="true">Sí</option>
        </select>
      </label>
      <label v-if="form.usaAuxiliaresLimpiezaBucal" class="form-control">
        <span class="label-text">Cuáles</span>
        <input v-model="form.detalleAuxiliaresLimpiezaBucal" type="text" class="input input-bordered w-full" maxlength="50">
      </label>

      <label class="form-control md:col-span-2">
        <span class="label-text">Frecuencia y tiempo de cepillado</span>
        <select v-model="form.frecuenciaCepillado" class="select select-bordered w-full">
          <option value="" disabled>Seleccione una opción</option>
          <option value="1 vez al día">1 vez al día</option>
          <option value="2 veces al día">2 veces al día</option>
          <option value="3 veces al día">3 veces al día</option>
          <option value="Después de cada comida">Después de cada comida</option>
          <option value="Ocasionalmente">Ocasionalmente</option>
        </select>
      </label>

      <label class="form-control">
        <span class="label-text">Infiltración de anestesia local</span>
        <select v-model="form.recibioAnestesiaLocal" class="select select-bordered w-full">
          <option :value="false">No</option>
          <option :value="true">Sí</option>
        </select>
      </label>
      <label v-if="form.recibioAnestesiaLocal" class="form-control">
        <span class="label-text">Complicaciones con anestesia</span>
        <select v-model="form.complicacionAnestesia" class="select select-bordered w-full">
          <option :value="false">No</option>
          <option :value="true">Sí</option>
        </select>
      </label>
      <label v-if="form.recibioAnestesiaLocal && form.complicacionAnestesia" class="form-control md:col-span-2">
        <span class="label-text">Describa complicación</span>
        <input v-model="form.detalleComplicacionAnestesia" type="text" class="input input-bordered w-full" maxlength="50">
      </label>

      <label class="form-control">
        <span class="label-text">Uso de remedio casero o naturista</span>
        <select v-model="form.usoRemedioCasero" class="select select-bordered w-full">
          <option :value="false">No</option>
          <option :value="true">Sí</option>
        </select>
      </label>
      <label v-if="form.usoRemedioCasero" class="form-control">
        <span class="label-text">Describa remedio utilizado</span>
        <input v-model="form.detalleRemedioCasero" type="text" class="input input-bordered w-full" maxlength="30">
      </label>

      <label class="form-control">
        <span class="label-text">Dolor o dificultad al masticar</span>
        <select v-model="form.dolorAlMasticar" class="select select-bordered w-full">
          <option :value="false">No</option>
          <option :value="true">Sí</option>
        </select>
      </label>
      <label v-if="form.dolorAlMasticar" class="form-control">
        <span class="label-text">Describa dolor al masticar</span>
        <input v-model="form.detalleDolorAlMasticar" type="text" class="input input-bordered w-full" maxlength="20">
      </label>

      <label class="form-control">
        <span class="label-text">Sangrado o inflamación</span>
        <select v-model="form.sangradoOInflamacion" class="select select-bordered w-full">
          <option :value="false">No</option>
          <option :value="true">Sí</option>
        </select>
      </label>
      <label v-if="form.sangradoOInflamacion" class="form-control">
        <span class="label-text">Describa sangrado o inflamación</span>
        <input v-model="form.detalleSangradoOInflamacion" type="text" class="input input-bordered w-full" maxlength="20">
      </label>

      <label class="form-control">
        <span class="label-text">Padece úlceras bucales o fuegos</span>
        <select v-model="form.ulcerasOrales" class="select select-bordered w-full">
          <option :value="false">No</option>
          <option :value="true">Sí</option>
        </select>
      </label>
      <label v-if="form.ulcerasOrales" class="form-control">
        <span class="label-text">Frecuencia</span>
        <input v-model="form.frecuenciaUlcerasOrales" type="text" class="input input-bordered w-full" maxlength="20">
      </label>

      <label class="form-control">
        <span class="label-text">Tiene hábitos bucales</span>
        <select v-model="form.habitosOrales" class="select select-bordered w-full">
          <option :value="false">No</option>
          <option :value="true">Sí</option>
        </select>
      </label>
      <label v-if="form.habitosOrales" class="form-control md:col-span-2">
        <span class="label-text">Cuáles hábitos</span>
        <input v-model="form.detalleHabitosOrales" type="text" class="input input-bordered w-full" maxlength="100">
      </label>

      <div class="md:col-span-2 rounded-box border border-base-300 p-4 space-y-3">
        <h3 class="font-medium">ATM</h3>
        <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
          <label class="label cursor-pointer justify-start gap-3 rounded-box border border-base-300 px-3 py-2">
            <input v-model="form.malestarAbrirBocas" type="checkbox" class="checkbox checkbox-primary checkbox-sm">
            <span class="label-text">Malestar al abrir la boca</span>
          </label>
          <label class="label cursor-pointer justify-start gap-3 rounded-box border border-base-300 px-3 py-2">
            <input v-model="form.malestarMovimientoLateral" type="checkbox" class="checkbox checkbox-primary checkbox-sm">
            <span class="label-text">Malestar en movimiento lateral</span>
          </label>
          <label class="label cursor-pointer justify-start gap-3 rounded-box border border-base-300 px-3 py-2">
            <input v-model="form.chasquidosCrepitaciones" type="checkbox" class="checkbox checkbox-primary checkbox-sm">
            <span class="label-text">Chasquidos o crepitaciones</span>
          </label>
          <label class="label cursor-pointer justify-start gap-3 rounded-box border border-base-300 px-3 py-2">
            <input v-model="form.desviacionMandibula" type="checkbox" class="checkbox checkbox-primary checkbox-sm">
            <span class="label-text">Desviación mandibular</span>
          </label>
        </div>
      </div>

      <label class="form-control md:col-span-2">
        <span class="label-text">Tejidos blandos y duros</span>
        <textarea v-model="form.tejidosBlandosDuros" class="textarea textarea-bordered w-full" rows="3" maxlength="500" />
      </label>
    </div>
  </section>
</template>
