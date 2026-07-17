import { defineStore } from 'pinia'
import { computed, ref } from 'vue'
import type { CaraDental, Enfermedad, EstadoHallazgo, Hallazgo, TipoOdontograma } from '@/types/Odontograma'

type TipoVista = 'inicial' | 'final'

const COLOR_DEFAULT = '#FFFFFF'

function clonarHallazgos(hallazgos: Hallazgo[]): Hallazgo[] {
    return hallazgos.map(h => ({ ...h, id: undefined, estado: h.estado }))
}

function crearHallazgoVacio(diente: number, cara: CaraDental): Hallazgo {
    return {
        diente,
        cara,
        enfermedad_id: 0,
        notas: '',
        estado: 'ACTIVO',
        en_plan: false,
    }
}

export const useOdontogramaStore = defineStore('odontograma', () => {
    const catalogoEnfermedades = ref<Enfermedad[]>([])
    const hallazgosInicial = ref<Hallazgo[]>([])
    const hallazgosFinal = ref<Hallazgo[]>([])
    const observacionesInicial = ref<string>('')
    const observacionesFinal = ref<string>('')
    const idInicial = ref<number | null>(null)
    const idFinal = ref<number | null>(null)

    const enfermedadesPorId = computed(() => {
        const mapa = new Map<number, Enfermedad>()
        for (const enfermedad of catalogoEnfermedades.value) {
            mapa.set(enfermedad.id, enfermedad)
        }
        return mapa
    })

    function getHallazgo(tipo: TipoVista, diente: number, cara: CaraDental): Hallazgo | undefined {
        const lista = tipo === 'inicial' ? hallazgosInicial.value : hallazgosFinal.value
        return lista.find(h => h.diente === diente && h.cara === cara)
    }

    function getColorCara(tipo: TipoVista, diente: number, cara: CaraDental): string {
        const hallazgo = getHallazgo(tipo, diente, cara)
        if (!hallazgo) {
            return cara === 'C' ? 'transparent' : COLOR_DEFAULT
        }

        if (tipo === 'final' && hallazgo.estado === 'RESUELTO') {
            return 'transparent'
        }

        const enfermedad = enfermedadesPorId.value.get(hallazgo.enfermedad_id)
        return enfermedad?.color ?? COLOR_DEFAULT
    }

    function agregarHallazgo(
        tipo: TipoVista,
        diente: number,
        cara: CaraDental,
        enfermedadId: number,
        notas = ''
    ): void {
        const lista = tipo === 'inicial' ? hallazgosInicial.value : hallazgosFinal.value
        const index = lista.findIndex(h => h.diente === diente && h.cara === cara)

        const hallazgo: Hallazgo = {
            diente,
            cara,
            enfermedad_id: enfermedadId,
            notas,
            estado: 'ACTIVO',
            en_plan: false,
        }

        if (index >= 0) {
            lista[index] = hallazgo
            return
        }

        lista.push(hallazgo)
    }

    function eliminarHallazgo(tipo: TipoVista, diente: number, cara: CaraDental): void {
        const lista = tipo === 'inicial' ? hallazgosInicial.value : hallazgosFinal.value
        const index = lista.findIndex(h => h.diente === diente && h.cara === cara)

        if (index >= 0) {
            lista.splice(index, 1)
        }
    }

    function actualizarEstado(tipo: TipoVista, diente: number, cara: CaraDental, estado: EstadoHallazgo): void {
        const hallazgo = getHallazgo(tipo, diente, cara)
        if (hallazgo) {
            hallazgo.estado = estado
        }
    }

    function resolverAfeccionFinal(diente: number, cara: CaraDental): void {
        actualizarEstado('final', diente, cara, 'RESUELTO')
    }

    function toggleEnPlanFinal(diente: number, cara: CaraDental): void {
        const hallazgo = getHallazgo('final', diente, cara)
        if (hallazgo) {
            hallazgo.en_plan = !hallazgo.en_plan
        }
    }

    function getHallazgosActivosPorTipo(tipo: TipoVista): Hallazgo[] {
        const lista = tipo === 'inicial' ? hallazgosInicial.value : hallazgosFinal.value
        return lista.filter(h => h.estado !== 'DESCARTADO')
    }

    function sincronizarFinalDesdeInicial(): void {
        hallazgosFinal.value = clonarHallazgos(hallazgosInicial.value)
    }

    function inicializar(
        catalogo: Enfermedad[],
        inicial: { id?: number; observaciones?: string | null; hallazgos: Hallazgo[] } | null,
        final?: { id?: number; observaciones?: string | null; hallazgos: Hallazgo[] } | null
    ): void {
        catalogoEnfermedades.value = catalogo
        idInicial.value = inicial?.id ?? null
        observacionesInicial.value = inicial?.observaciones ?? ''
        hallazgosInicial.value = inicial?.hallazgos.map(h => ({
            ...h,
            estado: h.estado ?? 'ACTIVO',
            en_plan: h.en_plan ?? false,
        })) ?? []

        idFinal.value = final?.id ?? null
        observacionesFinal.value = final?.observaciones ?? ''

        if (final) {
            hallazgosFinal.value = final.hallazgos.map(h => ({
                ...h,
                estado: h.estado ?? 'ACTIVO',
                en_plan: h.en_plan ?? false,
            }))
        } else {
            sincronizarFinalDesdeInicial()
        }
    }

    function resetStore(): void {
        catalogoEnfermedades.value = []
        hallazgosInicial.value = []
        hallazgosFinal.value = []
        observacionesInicial.value = ''
        observacionesFinal.value = ''
        idInicial.value = null
        idFinal.value = null
    }

    function prepararPayload(tipo: TipoVista, pacienteId: number, tipoOdontograma: TipoOdontograma) {
        const hallazgos = tipo === 'inicial' ? hallazgosInicial.value : hallazgosFinal.value
        const observaciones = tipo === 'inicial' ? observacionesInicial.value : observacionesFinal.value
        const id = tipo === 'inicial' ? idInicial.value : idFinal.value

        return {
            id,
            paciente_id: pacienteId,
            tipo: tipoOdontograma,
            observaciones,
            hallazgos: hallazgos.map(h => ({
                diente: h.diente,
                cara: h.cara,
                enfermedad_id: h.enfermedad_id,
                notas: h.notas,
                estado: h.estado,
                en_plan: h.en_plan,
            })),
        }
    }

    return {
        catalogoEnfermedades,
        hallazgosInicial,
        hallazgosFinal,
        observacionesInicial,
        observacionesFinal,
        idInicial,
        idFinal,
        enfermedadesPorId,
        getHallazgo,
        getColorCara,
        agregarHallazgo,
        eliminarHallazgo,
        actualizarEstado,
        resolverAfeccionFinal,
        toggleEnPlanFinal,
        getHallazgosActivosPorTipo,
        sincronizarFinalDesdeInicial,
        inicializar,
        resetStore,
        prepararPayload,
        crearHallazgoVacio,
    }
})
