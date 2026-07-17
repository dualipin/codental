<script setup lang="ts">
import StepAsignacion from './steps/StepAsignacion.vue'
import StepDatosPersonales from './steps/StepDatosPersonales.vue'
import StepHabitos from './steps/StepHabitos.vue'
import StepHistorialMedico from './steps/StepHistorialMedico.vue'
import StepHistorialOdontologico from './steps/StepHistorialOdontologico.vue'
import { useRegistrarPacienteWizard } from '@/composables/useRegistrarPacienteWizard'
import type { Doctor } from '@/types/Doctor'

const props = defineProps<{
  doctores: Doctor[]
  formularioPublico: boolean
}>()

const {
  formulario,
  pasos,
  pasoActual,
  totalPasos,
  esFormularioPublico,
  opcionesEnfermedad,
  doctorSeleccionado,
  getErroresPaso,
  pasoActualValido,
  siguientePaso,
  anteriorPaso,
  enviarFormulario,
} = useRegistrarPacienteWizard({
  doctores: props.doctores,
  formularioPublico: props.formularioPublico,
})

function etiquetaPaso(paso: unknown, indice: number): string {
  return typeof paso === 'string' && paso.trim() !== '' ? paso : `Paso ${indice + 1}`
}
</script>

<template>
  <div class="min-h-screen bg-linear-to-b from-base-200 to-base-100 py-6 px-4">
    <div class="mx-auto max-w-6xl">
      <div class="card border border-base-300 bg-base-100 shadow-xl">
        <div class="card-body gap-6">
          <div>
            <h1 class="text-2xl font-semibold text-primary">Cuestionario de Expediente Nuevo</h1>
            <p class="mt-1 text-sm text-base-content/70">
              Completa los pasos disponibles para guardar el expediente y pasar a la agenda.
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
              :formulario-publico="esFormularioPublico"
            />
            <StepHistorialMedico
              v-show="pasoActual === 2"
              :form="formulario"
              :formulario-publico="esFormularioPublico"
              :opciones-enfermedad="opcionesEnfermedad"
            />
            <StepHabitos
              v-show="pasoActual === 3"
              :form="formulario"
              :formulario-publico="esFormularioPublico"
            />
            <StepHistorialOdontologico
              v-show="pasoActual === 4"
              :form="formulario"
              :formulario-publico="esFormularioPublico"
            />
            <StepAsignacion
              v-show="!esFormularioPublico && pasoActual === 5"
              :form="formulario"
              :doctores="props.doctores"
              :doctor-seleccionado="doctorSeleccionado"
            />

            <p class="text-sm text-base-content/70">
              Los campos marcados con (*) son obligatorios
            </p>

            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-base-300 pt-4">
              <button type="button" class="btn btn-ghost" @click="anteriorPaso" :disabled="pasoActual === 1">
                Atrás
              </button>
              <div class="flex items-center gap-2">
                <span class="text-sm text-base-content/70">Paso {{ pasoActual }} de {{ totalPasos }}</span>
                <button v-if="pasoActual < totalPasos" type="button" class="btn btn-primary" @click="siguientePaso" :disabled="!pasoActualValido">
                  Siguiente
                </button>
                <button v-else @click="enviarFormulario" type="submit" class="btn btn-success" :disabled="formulario.processing">
                  <span v-if="formulario.processing" class="loading loading-spinner loading-sm" />
                  Guardar y ver agenda
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>