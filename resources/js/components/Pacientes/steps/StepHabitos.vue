<script setup lang="ts">
import { computed } from 'vue'
import type { PacienteExpedienteWizardForm } from '@/types/PacienteExpedienteWizard'

const { form } = defineProps<{
  form: PacienteExpedienteWizardForm
}>()

const hayConsumo = computed(() => form.consumeTabaco || form.consumeAlcohol || form.consumeDrogas)
</script>

<template>
  <section class="space-y-4">
    <h2 class="text-lg font-medium">Paso 3: Hábitos y antecedentes</h2>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
      <div class="form-control md:col-span-3">
        <span class="label-text mb-2">Consumo de</span>
        <div class="flex flex-wrap gap-4">
          <label class="label cursor-pointer gap-2 justify-start"><input v-model="form.consumeTabaco" type="checkbox" class="checkbox checkbox-primary checkbox-sm"><span class="label-text">Tabaco</span></label>
          <label class="label cursor-pointer gap-2 justify-start"><input v-model="form.consumeAlcohol" type="checkbox" class="checkbox checkbox-primary checkbox-sm"><span class="label-text">Alcohol</span></label>
          <label class="label cursor-pointer gap-2 justify-start"><input v-model="form.consumeDrogas" type="checkbox" class="checkbox checkbox-primary checkbox-sm"><span class="label-text">Drogas</span></label>
        </div>
      </div>

      <label v-if="hayConsumo" class="form-control md:col-span-2">
        <span class="label-text">Frecuencia de consumo</span>
        <select v-model="form.frecuenciaConsumo" class="select select-bordered w-full">
          <option value="" disabled>Seleccione una opción</option>
          <option value="Diario">Diario</option>
          <option value="Semanal">Semanal</option>
          <option value="Quincenal">Quincenal</option>
          <option value="Mensual">Mensual</option>
          <option value="Ocasional">Ocasional</option>
        </select>
      </label>
      <label class="form-control">
        <span class="label-text">Grupo sanguíneo</span>
        <select v-model="form.grupoSanguineo" class="select select-bordered w-full">
          <option value="" disabled>Seleccione una opción</option>
          <option value="A+">A+</option>
          <option value="A-">A-</option>
          <option value="B+">B+</option>
          <option value="B-">B-</option>
          <option value="AB+">AB+</option>
          <option value="AB-">AB-</option>
          <option value="O+">O+</option>
          <option value="O-">O-</option>
        </select>
      </label>
    </div>

    <div v-if="form.sexo === 'F'" class="rounded-box border border-base-300 p-4">
      <h3 class="mb-3 font-medium">Información femenina</h3>
      <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <label class="form-control">
          <span class="label-text">Actualmente está embarazada</span>
          <select v-model="form.embarazo" class="select select-bordered w-full">
            <option :value="false">No</option>
            <option :value="true">Sí</option>
          </select>
        </label>
        <label v-if="form.embarazo" class="form-control">
          <span class="label-text">Tiempo de gestación</span>
          <input v-model="form.tiempoGestacion" type="text" class="input input-bordered w-full" maxlength="10">
        </label>

        <label class="form-control">
          <span class="label-text">Estado de lactancia</span>
          <select v-model="form.lactancia" class="select select-bordered w-full">
            <option :value="false">No</option>
            <option :value="true">Sí</option>
          </select>
        </label>
        <label v-if="form.lactancia" class="form-control">
          <span class="label-text">Meses del bebé</span>
          <input v-model="form.mesesBebe" type="text" class="input input-bordered w-full" maxlength="2">
        </label>
      </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
      <label class="form-control">
        <span class="label-text">Actividad deportiva</span>
        <select v-model="form.actividadFisica" class="select select-bordered w-full">
          <option value="" disabled>Seleccione una opción</option>
          <option value="Ninguna">Ninguna</option>
          <option value="Ligera (1-2 veces/semana)">Ligera (1-2 veces/semana)</option>
          <option value="Moderada (3-4 veces/semana)">Moderada (3-4 veces/semana)</option>
          <option value="Intensa (5+ veces/semana)">Intensa (5+ veces/semana)</option>
        </select>
      </label>
      <label class="form-control">
        <span class="label-text">Alimentación</span>
        <select v-model="form.calidadDieta" class="select select-bordered w-full">
          <option value="Buena">Buena</option>
          <option value="Regular">Regular</option>
          <option value="Deficiente">Deficiente</option>
        </select>
      </label>
      <label class="form-control">
        <span class="label-text">Higiene personal</span>
        <select v-model="form.calidadHigiene" class="select select-bordered w-full">
          <option value="Buena">Buena</option>
          <option value="Regular">Regular</option>
          <option value="Deficiente">Deficiente</option>
        </select>
      </label>

      <label class="form-control">
        <span class="label-text">Internado o cirugía previa</span>
        <select v-model="form.tuvoCirugiaOHospitalizacion" class="select select-bordered w-full">
          <option :value="false">No</option>
          <option :value="true">Sí</option>
        </select>
      </label>
      <label v-if="form.tuvoCirugiaOHospitalizacion" class="form-control md:col-span-2">
        <span class="label-text">Describe</span>
        <textarea v-model="form.detalleCirugiaOHospitalizacion" class="textarea textarea-bordered w-full" rows="2" maxlength="150" />
      </label>

      <label class="form-control md:col-span-3">
        <span class="label-text">Padecimiento actual</span>
        <textarea v-model="form.padecimientoActual" class="textarea textarea-bordered w-full" rows="3" maxlength="500" />
      </label>
      <label class="form-control md:col-span-3">
        <span class="label-text">Interrogatorio por aparatos y sistemas</span>
        <textarea v-model="form.interrogatorioSistemas" class="textarea textarea-bordered w-full" rows="3" maxlength="500" />
      </label>
      <label class="form-control md:col-span-3">
        <span class="label-text">Exámenes de laboratorio y resultados</span>
        <textarea v-model="form.resultadosLaboratorio" class="textarea textarea-bordered w-full" rows="2" maxlength="500" />
      </label>
    </div>
  </section>
</template>
