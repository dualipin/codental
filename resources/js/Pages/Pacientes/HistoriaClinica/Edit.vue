<script setup lang="ts">
import StepHabitos from '@/components/Pacientes/steps/StepHabitos.vue'
import StepHistorialMedico from '@/components/Pacientes/steps/StepHistorialMedico.vue'
import StepHistorialOdontologico from '@/components/Pacientes/steps/StepHistorialOdontologico.vue'
import { opcionesEnfermedad } from '@/composables/usePacienteExpedienteWizard'
import {
  createDefaultPacienteExpedienteWizardForm,
  type PacienteExpedienteWizardForm,
} from '@/types/PacienteExpedienteWizard'
import { Head, Link, useForm } from '@inertiajs/vue3'

interface HistoriaClinicaPayload {
  antecedentes_hereditarios: string | null
  alergias: string | null
  medicacion_actual: string | null
  nombre_medico: string | null
  telefono_medico: string | null
  enfermedades_previas: string[]
  otras_enfermedades: string | null
  habitos_toxicos: Record<string, unknown>
  grupo_sanguineo: string | null
  ginecoobstetricos: Record<string, unknown>
  estilo_vida: Record<string, unknown>
  cirugias_hospitalizaciones: string | null
  padecimiento_actual: string | null
  interrogatorio_sistemas: string | null
  examenes_laboratorio: string | null
  antecedentes_bucodentales: Record<string, unknown>
  atm: Record<string, unknown>
  tejidos_blandos_duros: string | null
}

const props = defineProps<{
  paciente: {
    id: number
    nombre: string
    apellido_paterno: string
    apellido_materno: string | null
    sexo: 'M' | 'F' | 'O' | null
  }
  historiaClinica: HistoriaClinicaPayload
}>()

function limpiarTexto(valor: string): string {
  return valor.trim()
}

function crearFormulario(): PacienteExpedienteWizardForm {
  const predeterminado = createDefaultPacienteExpedienteWizardForm()
  const historia = props.historiaClinica
  const habitos = historia.habitos_toxicos ?? {}
  const gineco = historia.ginecoobstetricos ?? {}
  const estiloVida = historia.estilo_vida ?? {}
  const bucodentales = historia.antecedentes_bucodentales ?? {}
  const atm = historia.atm ?? {}

  return {
    ...predeterminado,
    sexo: props.paciente.sexo ?? predeterminado.sexo,
    antecedentesHereditarios: historia.antecedentes_hereditarios ?? '',
    alergias: historia.alergias ?? '',
    medicacionActual: historia.medicacion_actual ?? '',
    nombreMedico: historia.nombre_medico ?? '',
    telefonoMedico: historia.telefono_medico ?? '',
    enfermedades: Array.isArray(historia.enfermedades_previas) ? historia.enfermedades_previas : [],
    detalleOtrasEnfermedades: historia.otras_enfermedades ?? '',
    consumeTabaco: Boolean(habitos.tabaco),
    consumeAlcohol: Boolean(habitos.alcohol),
    consumeDrogas: Boolean(habitos.drogas),
    frecuenciaConsumo: String(habitos.frecuenciaConsumo ?? ''),
    grupoSanguineo: historia.grupo_sanguineo ?? '',
    embarazo: Boolean(gineco.embarazo),
    tiempoGestacion: String(gineco.tiempoGestacion ?? ''),
    lactancia: Boolean(gineco.lactancia),
    mesesBebe: String(gineco.mesesBebe ?? ''),
    actividadFisica: String(estiloVida.actividadFisica ?? ''),
    calidadDieta: String(estiloVida.calidadDieta ?? predeterminado.calidadDieta),
    calidadHigiene: String(estiloVida.calidadHigiene ?? predeterminado.calidadHigiene),
    tuvoCirugiaOHospitalizacion: Boolean(historia.cirugias_hospitalizaciones),
    detalleCirugiaOHospitalizacion: historia.cirugias_hospitalizaciones ?? '',
    padecimientoActual: historia.padecimiento_actual ?? '',
    interrogatorioSistemas: historia.interrogatorio_sistemas ?? '',
    resultadosLaboratorio: historia.examenes_laboratorio ?? '',
    ultimaRevisionDental: String(bucodentales.ultimaRevision ?? ''),
    motivoUltimaVisitaDental: String(bucodentales.motivoRevision ?? ''),
    usaAuxiliaresLimpiezaBucal: Boolean(bucodentales.auxiliaresLimpieza),
    detalleAuxiliaresLimpiezaBucal: String(bucodentales.detalleAuxiliares ?? ''),
    frecuenciaCepillado: String(bucodentales.frecuenciaCepillado ?? ''),
    recibioAnestesiaLocal: Boolean(bucodentales.anestesiaLocal),
    complicacionAnestesia: Boolean(bucodentales.complicacionAnestesia),
    detalleComplicacionAnestesia: String(bucodentales.detalleComplicacionAnestesia ?? ''),
    usoRemedioCasero: Boolean(bucodentales.remedioCasero),
    detalleRemedioCasero: String(bucodentales.detalleRemedioCasero ?? ''),
    dolorAlMasticar: Boolean(bucodentales.dolorMasticar),
    detalleDolorAlMasticar: String(bucodentales.detalleDolorMasticar ?? ''),
    sangradoOInflamacion: Boolean(bucodentales.sangradoInflamacion),
    detalleSangradoOInflamacion: String(bucodentales.detalleSangradoInflamacion ?? ''),
    ulcerasOrales: Boolean(bucodentales.ulcerasBucales),
    frecuenciaUlcerasOrales: String(bucodentales.frecuenciaUlceras ?? ''),
    habitosOrales: Boolean(bucodentales.habitosOrales),
    detalleHabitosOrales: String(bucodentales.detalleHabitosOrales ?? ''),
    malestarAbrirBocas: Boolean(atm.malestarAbrirBocas),
    malestarMovimientoLateral: Boolean(atm.malestarMovimientoLateral),
    chasquidosCrepitaciones: Boolean(atm.chasquidosCrepitaciones),
    desviacionMandibula: Boolean(atm.desviacionMandibula),
    tejidosBlandosDuros: historia.tejidos_blandos_duros ?? '',
  }
}

const formulario = useForm<PacienteExpedienteWizardForm>(crearFormulario())

function guardarHistoriaClinica() {
  formulario
    .transform(() => ({
      antecedentes_hereditarios: limpiarTexto(formulario.antecedentesHereditarios),
      alergias: limpiarTexto(formulario.alergias),
      medicacion_actual: limpiarTexto(formulario.medicacionActual),
      nombre_medico: limpiarTexto(formulario.nombreMedico),
      telefono_medico: limpiarTexto(formulario.telefonoMedico),
      enfermedades_previas: [...formulario.enfermedades],
      otras_enfermedades: limpiarTexto(formulario.detalleOtrasEnfermedades),
      habitos_toxicos: {
        tabaco: formulario.consumeTabaco,
        alcohol: formulario.consumeAlcohol,
        drogas: formulario.consumeDrogas,
        frecuenciaConsumo: limpiarTexto(formulario.frecuenciaConsumo),
      },
      grupo_sanguineo: limpiarTexto(formulario.grupoSanguineo),
      ginecoobstetricos: {
        embarazo: formulario.embarazo,
        tiempoGestacion: formulario.embarazo ? limpiarTexto(formulario.tiempoGestacion) : '',
        lactancia: formulario.lactancia,
        mesesBebe: formulario.lactancia ? limpiarTexto(formulario.mesesBebe) : '',
      },
      estilo_vida: {
        actividadFisica: limpiarTexto(formulario.actividadFisica),
        calidadDieta: formulario.calidadDieta,
        calidadHigiene: formulario.calidadHigiene,
      },
      cirugias_hospitalizaciones: formulario.tuvoCirugiaOHospitalizacion
        ? limpiarTexto(formulario.detalleCirugiaOHospitalizacion)
        : '',
      padecimiento_actual: limpiarTexto(formulario.padecimientoActual),
      interrogatorio_sistemas: limpiarTexto(formulario.interrogatorioSistemas),
      examenes_laboratorio: limpiarTexto(formulario.resultadosLaboratorio),
      antecedentes_bucodentales: {
        ultimaRevision: limpiarTexto(formulario.ultimaRevisionDental),
        motivoRevision: limpiarTexto(formulario.motivoUltimaVisitaDental),
        auxiliaresLimpieza: formulario.usaAuxiliaresLimpiezaBucal,
        detalleAuxiliares: formulario.usaAuxiliaresLimpiezaBucal
          ? limpiarTexto(formulario.detalleAuxiliaresLimpiezaBucal)
          : '',
        frecuenciaCepillado: limpiarTexto(formulario.frecuenciaCepillado),
        anestesiaLocal: formulario.recibioAnestesiaLocal,
        complicacionAnestesia: formulario.complicacionAnestesia,
        detalleComplicacionAnestesia: formulario.complicacionAnestesia
          ? limpiarTexto(formulario.detalleComplicacionAnestesia)
          : '',
        remedioCasero: formulario.usoRemedioCasero,
        detalleRemedioCasero: formulario.usoRemedioCasero
          ? limpiarTexto(formulario.detalleRemedioCasero)
          : '',
        dolorMasticar: formulario.dolorAlMasticar,
        detalleDolorMasticar: formulario.dolorAlMasticar
          ? limpiarTexto(formulario.detalleDolorAlMasticar)
          : '',
        sangradoInflamacion: formulario.sangradoOInflamacion,
        detalleSangradoInflamacion: formulario.sangradoOInflamacion
          ? limpiarTexto(formulario.detalleSangradoOInflamacion)
          : '',
        ulcerasBucales: formulario.ulcerasOrales,
        frecuenciaUlceras: formulario.ulcerasOrales
          ? limpiarTexto(formulario.frecuenciaUlcerasOrales)
          : '',
        habitosOrales: formulario.habitosOrales,
        detalleHabitosOrales: formulario.habitosOrales
          ? limpiarTexto(formulario.detalleHabitosOrales)
          : '',
      },
      atm: {
        malestarAbrirBocas: formulario.malestarAbrirBocas,
        malestarMovimientoLateral: formulario.malestarMovimientoLateral,
        chasquidosCrepitaciones: formulario.chasquidosCrepitaciones,
        desviacionMandibula: formulario.desviacionMandibula,
      },
      tejidos_blandos_duros: limpiarTexto(formulario.tejidosBlandosDuros),
    }))
    .patch(route('pacientes.historia-clinica.update', { paciente: props.paciente.id }), {
      preserveScroll: true,
    })
}
</script>

<template>
  <Head title="Historia clínica" />

  <section class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
      <div>
        <h1 class="text-2xl font-bold">Historia clínica completa</h1>
        <p class="mt-1 text-sm text-base-content/70">
          {{ paciente.nombre }} {{ paciente.apellido_paterno }} {{ paciente.apellido_materno ?? '' }}
        </p>
      </div>

      <Link :href="route('pacientes.show', { paciente: paciente.id })" class="btn btn-soft">Volver al expediente</Link>
    </div>

    <div v-if="Object.keys(formulario.errors).length" class="alert alert-error text-sm">
      <span>Hay errores en la historia clínica. Revisa los campos marcados.</span>
    </div>

    <form @submit.prevent="guardarHistoriaClinica" class="space-y-6">
      <div class="rounded-box border border-base-300 bg-base-100 p-6 lg:p-8">
        <StepHistorialMedico :form="formulario" :opciones-enfermedad="opcionesEnfermedad" />
      </div>

      <div class="rounded-box border border-base-300 bg-base-100 p-6 lg:p-8">
        <StepHabitos :form="formulario" />
      </div>

      <div class="rounded-box border border-base-300 bg-base-100 p-6 lg:p-8">
        <StepHistorialOdontologico :form="formulario" />
      </div>

      <div class="flex justify-end">
        <button type="submit" class="btn btn-primary" :disabled="formulario.processing">
          <span v-if="formulario.processing" class="loading loading-spinner loading-sm" />
          Guardar historia clínica
        </button>
      </div>
    </form>
  </section>
</template>
