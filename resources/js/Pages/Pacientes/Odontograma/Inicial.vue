<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'
import CatalogoEnfermedades from '@/components/Odontograma/CatalogoEnfermedades.vue'
import DienteSvg from '@/components/Odontograma/Diente.vue'
import ModalAfeccion from '@/components/Odontograma/ModalAfeccion.vue'
import OdontogramaSVG from '@/components/Odontograma/OdontogramaSVG.vue'
import OdontogramaTabla from '@/components/Odontograma/OdontogramaTabla.vue'
import { useOdontogramaStore } from '@/stores/odontograma'
import type { CaraDental, Diente, Enfermedad, Hallazgo, PacienteResumen } from '@/types/Odontograma'

const props = defineProps<{
    paciente: PacienteResumen
    catalogoEnfermedades: Enfermedad[]
    catalogoCaras: { id: number; nombre: string; codigo: CaraDental }[]
    dientes: Diente[]
    inicial: { id?: number; tipo: string; observaciones?: string | null; hallazgos: Hallazgo[] } | null
    final: { id?: number; tipo: string; observaciones?: string | null; hallazgos: Hallazgo[] } | null
}>()

const store = useOdontogramaStore()

const modalAbierto = ref(false)
const dienteSeleccionado = ref<number>(0)
const caraSeleccionada = ref<CaraDental>('O')

onMounted(() => {
    store.inicializar(
        props.catalogoEnfermedades,
        props.inicial,
        props.final
    )
})

const formulario = useForm({
    vista: 'inicial',
    observaciones: store.observacionesInicial,
    hallazgos: [] as Hallazgo[],
})

function abrirModal(diente: number, cara: CaraDental): void {
    dienteSeleccionado.value = diente
    caraSeleccionada.value = cara
    modalAbierto.value = true
}

function aplicarAfeccion(enfermedadId: number): void {
    store.agregarHallazgo('inicial', dienteSeleccionado.value, caraSeleccionada.value, enfermedadId)
    modalAbierto.value = false
}

function eliminarAfeccion(): void {
    store.eliminarHallazgo('inicial', dienteSeleccionado.value, caraSeleccionada.value)
    modalAbierto.value = false
}

function guardar(): void {
    formulario.observaciones = store.observacionesInicial
    formulario.hallazgos = store.hallazgosInicial

    formulario.post(route('pacientes.odontograma.guardar', props.paciente.id), {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="Odontograma Inicial" />

    <section class="space-y-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-bold">Odontograma Inicial</h1>
                <p class="text-sm text-base-content/70 mt-1">
                    {{ paciente.nombre }} {{ paciente.apellido_paterno }}
                    {{ paciente.apellido_materno }}
                </p>
            </div>

            <div class="flex gap-2">
                <Link
                    :href="route('pacientes.odontograma.final', paciente.id)"
                    class="btn btn-soft"
                >
                    Ver Final
                </Link>
                <Link :href="route('pacientes.index')" class="btn btn-soft">Volver</Link>
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-base">Leyenda</h2>
                <CatalogoEnfermedades />
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-base">Odontograma</h2>
                <OdontogramaSVG tipo="inicial" @seleccionar="abrirModal" />
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body space-y-4">
                <h2 class="card-title text-base">Registros</h2>

                <div>
                    <label class="label">
                        <span class="label-text">Observaciones generales</span>
                    </label>
                    <textarea
                        v-model="store.observacionesInicial"
                        class="textarea textarea-bordered w-full"
                        rows="3"
                        placeholder="Observaciones del odontograma inicial"
                    />
                </div>

                <OdontogramaTabla tipo="inicial" />
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <button
                type="button"
                class="btn btn-primary"
                :disabled="formulario.processing"
                @click="guardar"
            >
                Guardar odontograma inicial
            </button>
        </div>

        <ModalAfeccion
            :abierto="modalAbierto"
            :diente="dienteSeleccionado"
            :cara="caraSeleccionada"
            @cerrar="modalAbierto = false"
            @aplicar="aplicarAfeccion"
            @eliminar="eliminarAfeccion"
        />
    </section>
</template>
