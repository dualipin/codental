<script setup lang="ts">
import Diente from './Diente.vue'
import type { CaraDental } from '@/types/Odontograma'

const props = defineProps<{
    tipo: 'inicial' | 'final'
}>()

const emit = defineEmits<{
    (e: 'seleccionar', diente: number, cara: CaraDental): void
}>()

// Numeración FDI adultos organizada visualmente:
// Superior derecho (18-11), Superior izquierdo (21-28)
// Inferior izquierdo (38-31), Inferior derecho (41-48)
const filaSuperiorDerecha = [18, 17, 16, 15, 14, 13, 12, 11]
const filaSuperiorIzquierda = [21, 22, 23, 24, 25, 26, 27, 28]
const filaInferiorIzquierda = [38, 37, 36, 35, 34, 33, 32, 31]
const filaInferiorDerecha = [41, 42, 43, 44, 45, 46, 47, 48]

function onSeleccion(diente: number, cara: CaraDental): void {
    emit('seleccionar', diente, cara)
}
</script>

<template>
    <div class="w-full overflow-x-auto">
        <svg
            viewBox="0 0 1100 280"
            class="w-full h-auto min-w-[900px] text-base-content"
            xmlns="http://www.w3.org/2000/svg"
        >
            <!-- Fila superior derecha -->
            <g transform="translate(20, 20)">
                <Diente
                    v-for="(numero, index) in filaSuperiorDerecha"
                    :key="`${tipo}-SD-${numero}`"
                    :numero="numero"
                    :tipo="tipo"
                    :transform="`translate(${index * 70}, 0)`"
                    @seleccionar="onSeleccion"
                />
            </g>

            <!-- Fila superior izquierda -->
            <g transform="translate(580, 20)">
                <Diente
                    v-for="(numero, index) in filaSuperiorIzquierda"
                    :key="`${tipo}-SI-${numero}`"
                    :numero="numero"
                    :tipo="tipo"
                    :transform="`translate(${index * 70}, 0)`"
                    @seleccionar="onSeleccion"
                />
            </g>

            <!-- Fila inferior izquierda -->
            <g transform="translate(20, 150)">
                <Diente
                    v-for="(numero, index) in filaInferiorIzquierda"
                    :key="`${tipo}-II-${numero}`"
                    :numero="numero"
                    :tipo="tipo"
                    :transform="`translate(${index * 70}, 0)`"
                    @seleccionar="onSeleccion"
                />
            </g>

            <!-- Fila inferior derecha -->
            <g transform="translate(580, 150)">
                <Diente
                    v-for="(numero, index) in filaInferiorDerecha"
                    :key="`${tipo}-ID-${numero}`"
                    :numero="numero"
                    :tipo="tipo"
                    :transform="`translate(${index * 70}, 0)`"
                    @seleccionar="onSeleccion"
                />
            </g>
        </svg>
    </div>
</template>
