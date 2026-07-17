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

        <!-- Cara Oclusal/Incisal -->
        <polygon
            points="10,12 42,12 36,24 16,24"
            class="cara cursor-pointer hover:opacity-75 transition-opacity"
            :fill="colorCara('O')"
            stroke="currentColor"
            stroke-width="1"
            @click="onClick('O')"
        />

        <!-- Cara Mesial -->
        <polygon
            points="10,12 16,24 16,40 10,52"
            class="cara cursor-pointer hover:opacity-75 transition-opacity"
            :fill="colorCara('M')"
            stroke="currentColor"
            stroke-width="1"
            @click="onClick('M')"
        />

        <!-- Cara Distal -->
        <polygon
            points="42,12 36,24 36,40 42,52"
            class="cara cursor-pointer hover:opacity-75 transition-opacity"
            :fill="colorCara('D')"
            stroke="currentColor"
            stroke-width="1"
            @click="onClick('D')"
        />

        <!-- Cara Vestibular -->
        <polygon
            points="16,24 36,24 36,40 16,40"
            class="cara cursor-pointer hover:opacity-75 transition-opacity"
            :fill="colorCara('V')"
            stroke="currentColor"
            stroke-width="1"
            @click="onClick('V')"
        />

        <!-- Cara Lingual/Palatino -->
        <polygon
            points="10,52 42,52 36,40 16,40"
            class="cara cursor-pointer hover:opacity-75 transition-opacity"
            :fill="colorCara('L')"
            stroke="currentColor"
            stroke-width="1"
            @click="onClick('L')"
        />
    </g>
</template>

<style scoped>
.cara {
    vector-effect: non-scaling-stroke;
}
</style>
