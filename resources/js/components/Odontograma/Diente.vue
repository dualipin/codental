<script setup lang="ts">
import { useOdontogramaStore } from '@/stores/odontograma'
import type { CaraDental } from '@/types/Odontograma'

const props = defineProps<{
    numero: number
    tipo: 'inicial' | 'final'
}>()

const emit = defineEmits<{
    (e: 'seleccionar', diente: number, cara: CaraDental): void
}>()

const store = useOdontogramaStore()

function colorCara(cara: CaraDental): string {
    return store.getColorCara(props.tipo, props.numero, cara)
}

function onClick(cara: CaraDental): void {
    emit('seleccionar', props.numero, cara)
}
</script>

<template>
    <g class="diente">
        <text
            :x="26"
            y="6"
            text-anchor="middle"
            class="text-[8px] fill-current select-none pointer-events-none"
        >
            {{ numero }}
        </text>

        <path
            d="M 10,12 Q 26,8 42,12 Q 46,32 42,52 Q 26,56 10,52 Q 6,32 10,12 Z"
            fill="none"
            stroke="currentColor"
            stroke-width="1.5"
            class="pointer-events-none"
        />

        <path
            d="M 10,12 Q 26,8 42,12 L 36,24 L 16,24 Z"
            class="cara cursor-pointer hover:opacity-75 transition-opacity"
            :fill="colorCara('O')"
            stroke="currentColor"
            stroke-width="0.75"
            @click="onClick('O')"
        />

        <path
            d="M 10,12 Q 6,32 10,52 L 16,40 L 16,24 Z"
            class="cara cursor-pointer hover:opacity-75 transition-opacity"
            :fill="colorCara('M')"
            stroke="currentColor"
            stroke-width="0.75"
            @click="onClick('M')"
        />

        <path
            d="M 42,12 Q 46,32 42,52 L 36,40 L 36,24 Z"
            class="cara cursor-pointer hover:opacity-75 transition-opacity"
            :fill="colorCara('D')"
            stroke="currentColor"
            stroke-width="0.75"
            @click="onClick('D')"
        />

        <path
            d="M 16,24 L 36,24 L 36,40 L 16,40 Z"
            class="cara cursor-pointer hover:opacity-75 transition-opacity"
            :fill="colorCara('V')"
            stroke="currentColor"
            stroke-width="0.75"
            @click="onClick('V')"
        />

        <path
            d="M 10,52 Q 26,56 42,52 L 36,40 L 16,40 Z"
            class="cara cursor-pointer hover:opacity-75 transition-opacity"
            :fill="colorCara('L')"
            stroke="currentColor"
            stroke-width="0.75"
            @click="onClick('L')"
        />
    </g>
</template>

<style scoped>
.cara {
    vector-effect: non-scaling-stroke;
}
</style>
