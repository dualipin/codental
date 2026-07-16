import { defineStore } from 'pinia'
import {
  createDefaultRegistrarPacienteWizardForm,
  type RegistrarPacienteWizardForm,
} from '@/types/RegistrarPacienteWizard'

function clonarFormularioWizard(formulario: Partial<RegistrarPacienteWizardForm>): RegistrarPacienteWizardForm {
  const predeterminado = createDefaultRegistrarPacienteWizardForm()

  return {
    nombre: formulario.nombre ?? predeterminado.nombre,
    apellidoPaterno: formulario.apellidoPaterno ?? predeterminado.apellidoPaterno,
    apellidoMaterno: formulario.apellidoMaterno ?? predeterminado.apellidoMaterno,
    fechaNacimiento: formulario.fechaNacimiento ?? predeterminado.fechaNacimiento,
    sexo: formulario.sexo ?? predeterminado.sexo,
    ocupacion: formulario.ocupacion ?? predeterminado.ocupacion,
    estadoCivil: formulario.estadoCivil ?? predeterminado.estadoCivil,
    telefono: formulario.telefono ?? predeterminado.telefono,
    correo: formulario.correo ?? predeterminado.correo,
    estado: formulario.estado ?? predeterminado.estado,
    municipio: formulario.municipio ?? predeterminado.municipio,
    direccion: formulario.direccion ?? predeterminado.direccion,
    religion: formulario.religion ?? predeterminado.religion,
    antecedentesHereditarios: formulario.antecedentesHereditarios ?? predeterminado.antecedentesHereditarios,
    alergias: formulario.alergias ?? predeterminado.alergias,
    medicacionActual: formulario.medicacionActual ?? predeterminado.medicacionActual,
    nombreMedico: formulario.nombreMedico ?? predeterminado.nombreMedico,
    telefonoMedico: formulario.telefonoMedico ?? predeterminado.telefonoMedico,
    enfermedades: Array.isArray(formulario.enfermedades) ? [...formulario.enfermedades] : [...predeterminado.enfermedades],
    detalleOtrasEnfermedades: formulario.detalleOtrasEnfermedades ?? predeterminado.detalleOtrasEnfermedades,
    consumeTabaco: formulario.consumeTabaco ?? predeterminado.consumeTabaco,
    consumeAlcohol: formulario.consumeAlcohol ?? predeterminado.consumeAlcohol,
    consumeDrogas: formulario.consumeDrogas ?? predeterminado.consumeDrogas,
    frecuenciaConsumo: formulario.frecuenciaConsumo ?? predeterminado.frecuenciaConsumo,
    grupoSanguineo: formulario.grupoSanguineo ?? predeterminado.grupoSanguineo,
    embarazo: formulario.embarazo ?? predeterminado.embarazo,
    tiempoGestacion: formulario.tiempoGestacion ?? predeterminado.tiempoGestacion,
    lactancia: formulario.lactancia ?? predeterminado.lactancia,
    mesesBebe: formulario.mesesBebe ?? predeterminado.mesesBebe,
    actividadFisica: formulario.actividadFisica ?? predeterminado.actividadFisica,
    calidadDieta: formulario.calidadDieta ?? predeterminado.calidadDieta,
    calidadHigiene: formulario.calidadHigiene ?? predeterminado.calidadHigiene,
    tuvoCirugiaOHospitalizacion: formulario.tuvoCirugiaOHospitalizacion ?? predeterminado.tuvoCirugiaOHospitalizacion,
    detalleCirugiaOHospitalizacion: formulario.detalleCirugiaOHospitalizacion ?? predeterminado.detalleCirugiaOHospitalizacion,
    padecimientoActual: formulario.padecimientoActual ?? predeterminado.padecimientoActual,
    interrogatorioSistemas: formulario.interrogatorioSistemas ?? predeterminado.interrogatorioSistemas,
    resultadosLaboratorio: formulario.resultadosLaboratorio ?? predeterminado.resultadosLaboratorio,
    pesoKg: formulario.pesoKg ?? predeterminado.pesoKg,
    estaturaCm: formulario.estaturaCm ?? predeterminado.estaturaCm,
    temperaturaC: formulario.temperaturaC ?? predeterminado.temperaturaC,
    frecuenciaCardiaca: formulario.frecuenciaCardiaca ?? predeterminado.frecuenciaCardiaca,
    frecuenciaRespiratoria: formulario.frecuenciaRespiratoria ?? predeterminado.frecuenciaRespiratoria,
    presionArterial: formulario.presionArterial ?? predeterminado.presionArterial,
    ultimaRevisionDental: formulario.ultimaRevisionDental ?? predeterminado.ultimaRevisionDental,
    motivoUltimaVisitaDental: formulario.motivoUltimaVisitaDental ?? predeterminado.motivoUltimaVisitaDental,
    usaAuxiliaresLimpiezaBucal: formulario.usaAuxiliaresLimpiezaBucal ?? predeterminado.usaAuxiliaresLimpiezaBucal,
    detalleAuxiliaresLimpiezaBucal: formulario.detalleAuxiliaresLimpiezaBucal ?? predeterminado.detalleAuxiliaresLimpiezaBucal,
    frecuenciaCepillado: formulario.frecuenciaCepillado ?? predeterminado.frecuenciaCepillado,
    recibioAnestesiaLocal: formulario.recibioAnestesiaLocal ?? predeterminado.recibioAnestesiaLocal,
    complicacionAnestesia: formulario.complicacionAnestesia ?? predeterminado.complicacionAnestesia,
    detalleComplicacionAnestesia: formulario.detalleComplicacionAnestesia ?? predeterminado.detalleComplicacionAnestesia,
    usoRemedioCasero: formulario.usoRemedioCasero ?? predeterminado.usoRemedioCasero,
    detalleRemedioCasero: formulario.detalleRemedioCasero ?? predeterminado.detalleRemedioCasero,
    dolorAlMasticar: formulario.dolorAlMasticar ?? predeterminado.dolorAlMasticar,
    detalleDolorAlMasticar: formulario.detalleDolorAlMasticar ?? predeterminado.detalleDolorAlMasticar,
    sangradoOInflamacion: formulario.sangradoOInflamacion ?? predeterminado.sangradoOInflamacion,
    detalleSangradoOInflamacion: formulario.detalleSangradoOInflamacion ?? predeterminado.detalleSangradoOInflamacion,
    ulcerasOrales: formulario.ulcerasOrales ?? predeterminado.ulcerasOrales,
    frecuenciaUlcerasOrales: formulario.frecuenciaUlcerasOrales ?? predeterminado.frecuenciaUlcerasOrales,
    habitosOrales: formulario.habitosOrales ?? predeterminado.habitosOrales,
    detalleHabitosOrales: formulario.detalleHabitosOrales ?? predeterminado.detalleHabitosOrales,
    usuarioAsignado: formulario.usuarioAsignado ?? predeterminado.usuarioAsignado,
  }
}

export const useRegistrarPacienteWizardStore = defineStore('registrarPacienteWizard', {
  state: () => ({
    pasoActual: 1,
    formularioPublico: true,
    borrador: createDefaultRegistrarPacienteWizardForm(),
  }),
  actions: {
    setPasoActual(paso: number) {
      this.pasoActual = Math.max(1, Math.floor(paso))
    },
    setFormularioPublico(valor: boolean) {
      this.formularioPublico = valor
    },
    setBorrador(formulario: RegistrarPacienteWizardForm) {
      this.borrador = clonarFormularioWizard(formulario)
    },
    resetWizard() {
      this.pasoActual = 1
      this.formularioPublico = true
      this.borrador = createDefaultRegistrarPacienteWizardForm()
    },
  },
})
