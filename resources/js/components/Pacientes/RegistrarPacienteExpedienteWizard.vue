<script setup lang="ts">
import StepDatosPersonales from '@/components/Pacientes/steps/StepDatosPersonales.vue'
import StepHabitos from '@/components/Pacientes/steps/StepHabitos.vue'
import StepHistorialMedico from '@/components/Pacientes/steps/StepHistorialMedico.vue'
import StepHistorialOdontologico from '@/components/Pacientes/steps/StepHistorialOdontologico.vue'
import { usePacienteExpedienteWizard } from '@/composables/usePacienteExpedienteWizard'

const {
  formulario,
  pasos,
  pasoActual,
  totalPasos,
  opcionesEnfermedad,
  getErroresPaso,
  pasoActualValido,
  siguientePaso,
  anteriorPaso,
  enviarFormulario,
} = usePacienteExpedienteWizard()

function etiquetaPaso(paso: unknown, indice: number): string {
  return typeof paso === 'string' && paso.trim() !== '' ? paso : `Paso ${indice + 1}`
}

function siguientePasoConScroll() {
  siguientePaso()
  window.scrollTo({ top: 0, behavior: 'smooth' })
}
</script>

<template>
  <div class="rounded-box border border-base-300 bg-base-100">
    <div class="p-6 lg:p-8 space-y-6">
      <div>
        <h2 class="text-xl font-semibold text-primary">Cuestionario de expediente nuevo</h2>
        <p class="mt-1 text-sm text-base-content/70">
          Completa los pasos para registrar paciente e historia clinica.
        </p>
      </div>

      <ul class="steps steps-vertical sm:steps-horizontal w-full">
        <li
          v-for="(step, index) in pasos"
          :key="index"
          class="step"
          :class="{ 'step-primary': pasoActual >= index + 1 }"
        >
          {{ etiquetaPaso(step, index) }}
        </li>
      </ul>

      <div v-if="getErroresPaso(pasoActual).length" class="alert alert-error text-sm">
        <span>Hay errores en este paso. Revisa los campos marcados.</span>
      </div>

      <form @submit.prevent class="space-y-6">
        <StepDatosPersonales
          v-show="pasoActual === 1"
          :form="formulario"
        />
        <StepHistorialMedico
          v-show="pasoActual === 2"
          :form="formulario"
          :opciones-enfermedad="opcionesEnfermedad"
        />
        <StepHabitos
          v-show="pasoActual === 3"
          :form="formulario"
        />
        <StepHistorialOdontologico
          v-show="pasoActual === 4"
          :form="formulario"
        />

        <p class="text-sm text-base-content/70">
          Los campos marcados con (*) son obligatorios
        </p>

        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-base-300 pt-4">
          <button type="button" class="btn btn-ghost" @click="anteriorPaso" :disabled="pasoActual === 1">
            Atras
          </button>
          <div class="flex items-center gap-2">
            <span class="text-sm text-base-content/70">Paso {{ pasoActual }} de {{ totalPasos }}</span>
            <button
              v-if="pasoActual < totalPasos"
              type="button"
              class="btn btn-primary"
              @click="siguientePasoConScroll"
              :disabled="!pasoActualValido"
            >
              Siguiente
            </button>
            <button v-else @click="enviarFormulario" type="submit" class="btn btn-success" :disabled="formulario.processing">
              <span v-if="formulario.processing" class="loading loading-spinner loading-sm" />
              Guardar paciente
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
