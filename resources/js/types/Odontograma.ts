export type CaraDental = 'V' | 'L' | 'M' | 'D' | 'O' | 'C'

export type EstadoHallazgo = 'ACTIVO' | 'RESUELTO' | 'DESCARTADO'

export type TipoOdontograma = 'evaluacion_inicial' | 'seguimiento' | 'alta' | 'reevaluacion'

export interface Enfermedad {
    id: number
    nombre: string
    descripcion: string | null
    color: string
}

export interface CaraCatalogo {
    id: number
    nombre: string
    codigo: CaraDental
}

export interface Diente {
    id: number
    numero_fdi: number
    nombre: string
    cuadrante: string
    tipo: string
    posicion: string
}

export interface Hallazgo {
    id?: number
    diente: number
    cara: CaraDental
    enfermedad_id: number
    notas: string
    estado: EstadoHallazgo
    en_plan: boolean
}

export interface OdontogramaPayload {
    id?: number
    paciente_id: number
    tipo: TipoOdontograma
    observaciones: string | null
    hallazgos: Hallazgo[]
}

export interface PacienteResumen {
    id: number
    nombre: string
    apellido_paterno: string
    apellido_materno: string | null
}
