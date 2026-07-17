import { computed, ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import {
  createDefaultPacienteExpedienteWizardForm,
  type OpcionEnfermedad,
  type PacienteExpedienteWizardForm,
} from '@/types/PacienteExpedienteWizard'

const camposRequeridos: Record<number, Array<keyof PacienteExpedienteWizardForm>> = {
  1: ['nombre', 'apellidoPaterno', 'fechaNacimiento', 'sexo', 'telefono'],
  2: ['antecedentesHereditarios'],
  3: [],
  4: [],
}

export const opcionesEnfermedad: OpcionEnfermedad[] = [
  { key: 'diabetes', label: 'Diabetes' },
  { key: 'vih', label: 'VIH' },
  { key: 'asma', label: 'Asma' },
  { key: 'hipertension', label: 'Hipertension' },
  { key: 'sida', label: 'Sida' },
  { key: 'infartos', label: 'Infartos' },
  { key: 'cancer', label: 'Cancer' },
  { key: 'vph', label: 'VPH' },
  { key: 'epilepsia', label: 'Epilepsia' },
  { key: 'enfermedades_mentales', label: 'Enfermedades mentales' },
  { key: 'enfermedades_cardiacas', label: 'Enfermedades cardiacas' },
  { key: 'hepatitis', label: 'Hepatitis' },
  { key: 'enfermedades_hepaticas', label: 'Enfermedades hepáticas' },
  { key: 'enfermedades_glandulares', label: 'Enfermedades glandulares' },
  { key: 'anemia', label: 'Anemia' },
  { key: 'enfermedades_metabolicas', label: 'Enfermedades metabólicas' },
  { key: 'enfermedades_respiratorias', label: 'Enfermedades respiratorias' },
  { key: 'tuberculosis', label: 'Tuberculosis' },
  { key: 'ets', label: 'Enfermedades de transmision sexual' },
  { key: 'enfermedades_digestivas', label: 'Enfermedades digestivas' },
  { key: 'otras', label: 'Otras enfermedades' },
  { key: 'enfermedades_urinarias', label: 'Enfermedades urinarias' },
  { key: 'enfermedades_oseas', label: 'Enfermedades oseas' },
]

function limpiarTexto(valor: string): string {
  return valor.trim()
}

export function usePacienteExpedienteWizard() {
  const formulario = useForm<PacienteExpedienteWizardForm>(
    createDefaultPacienteExpedienteWizardForm(),
  )

  const pasoActual = ref(1)
  const pasos = ['Personales', 'Medicos', 'Habitos', 'Odontologico'] as const
  const totalPasos = computed(() => pasos.length)

  watch(
    () => formulario.sexo,
    (nuevoValor) => {
      if (nuevoValor !== 'F') {
        formulario.embarazo = false
        formulario.tiempoGestacion = ''
        formulario.lactancia = false
        formulario.mesesBebe = ''
      }
    },
  )

  watch(
    () => [formulario.consumeTabaco, formulario.consumeAlcohol, formulario.consumeDrogas],
    (valores) => {
      if (!valores.some(Boolean)) {
        formulario.frecuenciaConsumo = ''
      }
    },
  )

  watch(
    () => formulario.enfermedades,
    (nuevoValor) => {
      if (!nuevoValor.includes('otras')) {
        formulario.detalleOtrasEnfermedades = ''
      }
    },
  )

  watch(
    () => formulario.tuvoCirugiaOHospitalizacion,
    (nuevoValor) => {
      if (!nuevoValor) {
        formulario.detalleCirugiaOHospitalizacion = ''
      }
    },
  )

  watch(
    () => formulario.usaAuxiliaresLimpiezaBucal,
    (nuevoValor) => {
      if (!nuevoValor) {
        formulario.detalleAuxiliaresLimpiezaBucal = ''
      }
    },
  )

  watch(
    () => formulario.recibioAnestesiaLocal,
    (nuevoValor) => {
      if (!nuevoValor) {
        formulario.complicacionAnestesia = false
        formulario.detalleComplicacionAnestesia = ''
      }
    },
  )

  watch(
    () => formulario.complicacionAnestesia,
    (nuevoValor) => {
      if (!nuevoValor) {
        formulario.detalleComplicacionAnestesia = ''
      }
    },
  )

  watch(
    () => formulario.usoRemedioCasero,
    (nuevoValor) => {
      if (!nuevoValor) {
        formulario.detalleRemedioCasero = ''
      }
    },
  )

  watch(
    () => formulario.dolorAlMasticar,
    (nuevoValor) => {
      if (!nuevoValor) {
        formulario.detalleDolorAlMasticar = ''
      }
    },
  )

  watch(
    () => formulario.sangradoOInflamacion,
    (nuevoValor) => {
      if (!nuevoValor) {
        formulario.detalleSangradoOInflamacion = ''
      }
    },
  )

  watch(
    () => formulario.ulcerasOrales,
    (nuevoValor) => {
      if (!nuevoValor) {
        formulario.frecuenciaUlcerasOrales = ''
      }
    },
  )

  watch(
    () => formulario.habitosOrales,
    (nuevoValor) => {
      if (!nuevoValor) {
        formulario.detalleHabitosOrales = ''
      }
    },
  )

  const pasoActualValido = computed(() => {
    const campos = camposRequeridos[pasoActual.value] ?? []
    if (campos.length === 0) return true
    return campos.every((campo) => {
      const valor = formulario[campo]
      if (typeof valor === 'string') return valor.trim() !== ''
      return valor != null
    })
  })

  function siguientePaso() {
    if (pasoActual.value < totalPasos.value) {
      pasoActual.value += 1
    }
  }

  function anteriorPaso() {
    if (pasoActual.value > 1) {
      pasoActual.value -= 1
    }
  }

  function getErroresPaso(paso: number): string[] {
    const gruposCamposErrores: Record<number, string[]> = {
      1: [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'sexo',
        'ocupacion',
        'estado_civil',
        'telefono',
        'correo_electronico',
        'estado',
        'municipio',
        'direccion',
        'religion',
      ],
      2: [
        'antecedentes_hereditarios',
        'alergias',
        'medicacion_actual',
        'nombre_medico',
        'telefono_medico',
        'enfermedades_previas',
        'otras_enfermedades',
      ],
      3: [
        'habitos_toxicos',
        'grupo_sanguineo',
        'ginecoobstetricos',
        'estilo_vida',
        'cirugias_hospitalizaciones',
        'padecimiento_actual',
        'interrogatorio_sistemas',
        'examenes_laboratorio',
      ],
      4: ['antecedentes_bucodentales', 'atm', 'tejidos_blandos_duros'],
    }

    const campos = gruposCamposErrores[paso] ?? []
    const erroresFormulario = formulario.errors as Record<string, string | undefined>

    return campos
      .map((campo) => erroresFormulario[String(campo)])
      .filter((error): error is string => Boolean(error))
  }

  function enviarFormulario() {
    formulario
      .transform(() => ({
        nombre: limpiarTexto(formulario.nombre),
        apellido_paterno: limpiarTexto(formulario.apellidoPaterno),
        apellido_materno: limpiarTexto(formulario.apellidoMaterno),
        fecha_nacimiento: formulario.fechaNacimiento,
        sexo: formulario.sexo,
        ocupacion: limpiarTexto(formulario.ocupacion),
        estado_civil: limpiarTexto(formulario.estadoCivil),
        telefono: limpiarTexto(formulario.telefono),
        correo_electronico: limpiarTexto(formulario.correo),
        estado: limpiarTexto(formulario.estado),
        municipio: limpiarTexto(formulario.municipio),
        direccion: limpiarTexto(formulario.direccion),
        religion: limpiarTexto(formulario.religion),
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
          malestarAbrirBocas: false,
          malestarMovimientoLateral: false,
          chasquidosCrepitaciones: false,
          desviacionMandibula: false,
        },
        tejidos_blandos_duros: '',
      }))
      .post(route('pacientes.store', {}, false), {
        preserveScroll: true,
        onError: () => {
          for (let paso = 1; paso <= totalPasos.value; paso += 1) {
            if (getErroresPaso(paso).length > 0) {
              pasoActual.value = paso
              break
            }
          }
        },
      })
  }

  return {
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
  }
}
