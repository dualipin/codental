import {computed, watch} from 'vue'
import {useForm} from '@inertiajs/vue3'
import {useRegistrarPacienteWizardStore} from '@/stores/registrarPacienteWizard'
import type {Doctor} from '@/types/Doctor'
import {
    createDefaultRegistrarPacienteWizardForm,
    type OpcionEnfermedad,
    type RegistrarPacienteWizardForm,
    type RegistrarPacienteWizardPayload,
} from '@/types/RegistrarPacienteWizard'

const camposRequeridos: Record<number, Array<keyof RegistrarPacienteWizardForm>> = {
    1: ['nombre', 'apellidoPaterno', 'fechaNacimiento', 'sexo', 'telefono'],
    2: ['antecedentesHereditarios'],
    3: [],
    4: [],
    5: ['usuarioAsignado'],
}

export const opcionesEnfermedad: OpcionEnfermedad[] = [
    {key: 'diabetes', label: 'Diabetes'},
    {key: 'vih', label: 'VIH'},
    {key: 'asma', label: 'Asma'},
    {key: 'hipertension', label: 'Hipertension'},
    {key: 'sida', label: 'Sida'},
    {key: 'infartos', label: 'Infartos'},
    {key: 'cancer', label: 'Cancer'},
    {key: 'vph', label: 'VPH'},
    {key: 'epilepsia', label: 'Epilepsia'},
    {key: 'enfermedades_mentales', label: 'Enfermedades mentales'},
    {key: 'enfermedades_cardiacas', label: 'Enfermedades cardiacas'},
    {key: 'hepatitis', label: 'Hepatitis'},
    {key: 'enfermedades_hepaticas', label: 'Enfermedades hepáticas'},
    {key: 'enfermedades_glandulares', label: 'Enfermedades glandulares'},
    {key: 'anemia', label: 'Anemia'},
    {key: 'enfermedades_metabolicas', label: 'Enfermedades metabólicas'},
    {key: 'enfermedades_respiratorias', label: 'Enfermedades respiratorias'},
    {key: 'tuberculosis', label: 'Tuberculosis'},
    {key: 'ets', label: 'Enfermedades de transmision sexual'},
    {key: 'enfermedades_digestivas', label: 'Enfermedades digestivas'},
    {key: 'otras', label: 'Otras enfermedades'},
    {key: 'enfermedades_urinarias', label: 'Enfermedades urinarias'},
    {key: 'enfermedades_oseas', label: 'Enfermedades oseas'},
]

export const pasosWizard = ['Personales', 'Medicos', 'Habitos', 'Odontologico', 'Asignacion'] as const
export const pasosWizardPublico = pasosWizard.slice(0, 4)

const gruposCamposErrores: Record<number, Array<keyof RegistrarPacienteWizardPayload>> = {
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
    5: ['usuario_asignado'],
}

function clonarFormulario(formulario: RegistrarPacienteWizardForm): RegistrarPacienteWizardForm {
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

function limpiarTexto(valor: string): string {
    return valor.trim()
}

function construirPayload(
    formulario: RegistrarPacienteWizardForm,
    formularioPublico: boolean,
): RegistrarPacienteWizardPayload {
    const hayConsumo = formulario.consumeTabaco || formulario.consumeAlcohol || formulario.consumeDrogas

    return {
        formulario_publico: formularioPublico,
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
        motivo_consulta: '',
        usuario_asignado: formularioPublico ? null : (formulario.usuarioAsignado || null),
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
            frecuenciaConsumo: hayConsumo ? limpiarTexto(formulario.frecuenciaConsumo) : '',
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
    }
}

export function useRegistrarPacienteWizard(props: { doctores: Doctor[]; formularioPublico: boolean; submitRoute: string }) {
    const store = useRegistrarPacienteWizardStore()
    store.setFormularioPublico(props.formularioPublico)

    const formulario = useForm<RegistrarPacienteWizardForm>(
        clonarFormulario(store.borrador ?? createDefaultRegistrarPacienteWizardForm()),
    )

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

    watch(
        () => formulario,
        () => {
            store.setBorrador(formulario.data() as RegistrarPacienteWizardForm)
        },
        {deep: true, immediate: true},
    )

    const esFormularioPublico = computed(() => props.formularioPublico)
    const pasos = computed(() => {
        const origen = esFormularioPublico.value ? pasosWizardPublico : pasosWizard
        return origen.map((paso, indice) => (typeof paso === 'string' && paso.trim() !== '' ? paso : `Paso ${indice + 1}`))
    })
    const totalPasos = computed(() => pasos.value.length)

    const pasoActual = computed({
        get: () => store.pasoActual,
        set: (valor: number) => store.setPasoActual(valor),
    })

    watch(
        totalPasos,
        (valor) => {
            if (pasoActual.value > valor) {
                pasoActual.value = valor
            }
        },
        {immediate: true},
    )

    const doctorSeleccionado = computed(() => {
        if (!formulario.usuarioAsignado) {
            return null
        }

        return props.doctores.find((doctor) => String(doctor.id) === String(formulario.usuarioAsignado)) ?? null
    })

    function getErroresPaso(paso: number): string[] {
        const campos = gruposCamposErrores[paso] ?? []
        const erroresFormulario = formulario.errors as Record<string, string | undefined>

        return campos
            .map((campo) => erroresFormulario[String(campo)])
            .filter((error): error is string => Boolean(error))
    }

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

    function enviarFormulario() {
        const payload = construirPayload(formulario, esFormularioPublico.value)

        const path = props.submitRoute

        formulario
            .transform(() => payload)
            .post(path, {
                preserveScroll: true,
                onSuccess: () => {
                    store.resetWizard()
                },
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
        esFormularioPublico,
        opcionesEnfermedad,
        doctorSeleccionado,
        getErroresPaso,
        pasoActualValido,
        siguientePaso,
        anteriorPaso,
        enviarFormulario,
    }
}


