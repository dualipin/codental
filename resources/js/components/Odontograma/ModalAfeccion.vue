<script setup lang="ts">
import { computed } from 'vue'
import { useOdontogramaStore } from '@/stores/odontograma'
import type { CaraDental } from '@/types/Odontograma'

const props = defineProps<{
    abierto: boolean
    diente: number
    cara: CaraDental
}>()

const emit = defineEmits<{
    (e: 'cerrar'): void
    (e: 'aplicar', enfermedadId: number): void
    (e: 'eliminar'): void
}>()

const store = useOdontogramaStore()

const tituloCara = computed(() => {
    const labels: Record<CaraDental, string> = {
        V: 'Vestibular',
        L: 'Lingual / Palatino',
        M: 'Mesial',
        D: 'Distal',
        O: 'Oclusal / Incisal',
        C: 'Completo',
    }
    return labels[props.cara]
})

const enfermedadActual = computed(() => {
    const hallazgo = store.getHallazgo('inicial', props.diente, props.cara)
    return hallazgo?.enfermedad_id
})

function seleccionar(id: number): void {
    emit('aplicar', id)
}

function quitar(): void {
    emit('eliminar')
}
</script>

<template>
    <dialog class="modal" :class="{ 'modal-open': abierto }">
        <div class="modal-box max-w-md">
            <h3 class="font-bold text-lg">
                Diente {{ diente }} - {{ tituloCara }}
            </h3>
            <p class="text-sm text-base-content/70 mt-1">
                Selecciona una enfermedad o condición para esta cara.
            </p>

            <div class="mt-4 grid grid-cols-1 gap-2 max-h-80 overflow-y-auto">
                <button
                    v-for="enfermedad in store.catalogoEnfermedades"
                    :key="enfermedad.id"
                    type="button"
                    class="btn btn-block justify-start"
                    :class="{ 'btn-primary': enfermedadActual === enfermedad.id }"
                    @click="seleccionar(enfermedad.id)"
                >
                    <span
                        class="w-4 h-4 rounded-full inline-block"
                        :style="{ backgroundColor: enfermedad.color }"
                    />
                    <span>{{ enfermedad.nombre }}</span>
                </button>
            </div>

            <div class="modal-action">
                <button v-if="enfermedadActual" type="button" class="btn btn-error btn-soft" @click="quitar">
                    Quitar afección
                </button>
                <button type="button" class="btn" @click="$emit('cerrar')">Cancelar</button>
            </div>
        </div>

        <form method="dialog" class="modal-backdrop" @click="$emit('cerrar')">
            <button>close</button>
        </form>
    </dialog>
</template>
