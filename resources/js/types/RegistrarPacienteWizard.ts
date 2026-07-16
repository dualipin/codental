export interface OpcionEnfermedad {
  key: string
  label: string
}

export interface HabitosToxicosForm {
  tabaco: boolean
  alcohol: boolean
  drogas: boolean
  frecuenciaConsumo: string
}

export interface GinecoobstetricosForm {
  embarazo: boolean
  tiempoGestacion: string
  lactancia: boolean
  mesesBebe: string
}

export interface EstiloVidaForm {
  actividadFisica: string
  calidadDieta: string
  calidadHigiene: string
}

export interface CirugiasHospitalizacionesForm {
  tuvoCirugia: boolean
  detalles: string
}

export interface AntecedentesBucodentalesForm {
  ultimaRevision: string
  motivoRevision: string
  auxiliaresLimpieza: boolean
  detalleAuxiliares: string
  frecuenciaCepillado: string
  anestesiaLocal: boolean
  complicacionAnestesia: boolean
  detalleComplicacionAnestesia: string
  remedioCasero: boolean
  detalleRemedioCasero: string
  dolorMasticar: boolean
  detalleDolorMasticar: string
  sangradoInflamacion: boolean
  detalleSangradoInflamacion: string
  ulcerasBucales: boolean
  frecuenciaUlceras: string
  habitosOrales: boolean
  detalleHabitosOrales: string
}

export interface AtmForm {
  malestarAbrirBocas: boolean
  malestarMovimientoLateral: boolean
  chasquidosCrepitaciones: boolean
  desviacionMandibula: boolean
}

export interface RegistrarPacienteWizardForm {
  nombre: string
  apellidoPaterno: string
  apellidoMaterno: string
  fechaNacimiento: string
  sexo: 'M' | 'F' | 'O'
  ocupacion: string
  estadoCivil: string
  telefono: string
  correo: string
  estado: string
  municipio: string
  direccion: string
  religion: string
  antecedentesHereditarios: string
  alergias: string
  medicacionActual: string
  nombreMedico: string
  telefonoMedico: string
  enfermedades: string[]
  detalleOtrasEnfermedades: string
  consumeTabaco: boolean
  consumeAlcohol: boolean
  consumeDrogas: boolean
  frecuenciaConsumo: string
  grupoSanguineo: string
  embarazo: boolean
  tiempoGestacion: string
  lactancia: boolean
  mesesBebe: string
  actividadFisica: string
  calidadDieta: string
  calidadHigiene: string
  tuvoCirugiaOHospitalizacion: boolean
  detalleCirugiaOHospitalizacion: string
  padecimientoActual: string
  interrogatorioSistemas: string
  resultadosLaboratorio: string
  pesoKg: string
  estaturaCm: string
  temperaturaC: string
  frecuenciaCardiaca: string
  frecuenciaRespiratoria: string
  presionArterial: string
  ultimaRevisionDental: string
  motivoUltimaVisitaDental: string
  usaAuxiliaresLimpiezaBucal: boolean
  detalleAuxiliaresLimpiezaBucal: string
  frecuenciaCepillado: string
  recibioAnestesiaLocal: boolean
  complicacionAnestesia: boolean
  detalleComplicacionAnestesia: string
  usoRemedioCasero: boolean
  detalleRemedioCasero: string
  dolorAlMasticar: boolean
  detalleDolorAlMasticar: string
  sangradoOInflamacion: boolean
  detalleSangradoOInflamacion: string
  ulcerasOrales: boolean
  frecuenciaUlcerasOrales: string
  habitosOrales: boolean
  detalleHabitosOrales: string
  usuarioAsignado: string | number
}

export interface RegistrarPacienteWizardPayload {
  formulario_publico: boolean
  nombre: string
  apellido_paterno: string
  apellido_materno: string
  fecha_nacimiento: string
  sexo: 'M' | 'F' | 'O'
  ocupacion: string
  estado_civil: string
  telefono: string
  correo_electronico: string
  estado: string
  municipio: string
  direccion: string
  religion: string
  motivo_consulta: string
  usuario_asignado?: string | number | null
  antecedentes_hereditarios: string
  alergias: string
  medicacion_actual: string
  nombre_medico: string
  telefono_medico: string
  enfermedades_previas: string[]
  otras_enfermedades: string
  habitos_toxicos: HabitosToxicosForm
  grupo_sanguineo: string
  ginecoobstetricos: GinecoobstetricosForm
  estilo_vida: EstiloVidaForm
  cirugias_hospitalizaciones: string
  padecimiento_actual: string
  interrogatorio_sistemas: string
  examenes_laboratorio: string
  antecedentes_bucodentales: AntecedentesBucodentalesForm
  atm: AtmForm
  tejidos_blandos_duros: string
}

export function createDefaultRegistrarPacienteWizardForm(): RegistrarPacienteWizardForm {
  return {
    nombre: '',
    apellidoPaterno: '',
    apellidoMaterno: '',
    fechaNacimiento: '',
    sexo: 'M',
    ocupacion: '',
    estadoCivil: '',
    telefono: '',
    correo: '',
    estado: 'Tabasco',
    municipio: 'Macuspana',
    direccion: '',
    religion: '',
    antecedentesHereditarios: '',
    alergias: '',
    medicacionActual: '',
    nombreMedico: '',
    telefonoMedico: '',
    enfermedades: [],
    detalleOtrasEnfermedades: '',
    consumeTabaco: false,
    consumeAlcohol: false,
    consumeDrogas: false,
    frecuenciaConsumo: '',
    grupoSanguineo: '',
    embarazo: false,
    tiempoGestacion: '',
    lactancia: false,
    mesesBebe: '',
    actividadFisica: '',
    calidadDieta: 'Buena',
    calidadHigiene: 'Buena',
    tuvoCirugiaOHospitalizacion: false,
    detalleCirugiaOHospitalizacion: '',
    padecimientoActual: '',
    interrogatorioSistemas: '',
    resultadosLaboratorio: '',
    pesoKg: '',
    estaturaCm: '',
    temperaturaC: '',
    frecuenciaCardiaca: '',
    frecuenciaRespiratoria: '',
    presionArterial: '',
    ultimaRevisionDental: '',
    motivoUltimaVisitaDental: '',
    usaAuxiliaresLimpiezaBucal: false,
    detalleAuxiliaresLimpiezaBucal: '',
    frecuenciaCepillado: '',
    recibioAnestesiaLocal: false,
    complicacionAnestesia: false,
    detalleComplicacionAnestesia: '',
    usoRemedioCasero: false,
    detalleRemedioCasero: '',
    dolorAlMasticar: false,
    detalleDolorAlMasticar: '',
    sangradoOInflamacion: false,
    detalleSangradoOInflamacion: '',
    ulcerasOrales: false,
    frecuenciaUlcerasOrales: '',
    habitosOrales: false,
    detalleHabitosOrales: '',
    usuarioAsignado: '',
  }
}



