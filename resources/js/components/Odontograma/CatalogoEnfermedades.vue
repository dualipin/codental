<script setup lang="ts">
import { useOdontogramaStore } from '@/stores/odontograma'

const store = useOdontogramaStore()
</script>

<template>
    <div class="flex flex-wrap gap-2">
        <div
            v-for="enfermedad in store.catalogoEnfermedades"
            :key="enfermedad.id"
            class="badge badge-lg gap-2 border-base-300"
        >
            <span
                class="w-3 h-3 rounded-full inline-block"
                :style="{ backgroundColor: enfermedad.color }"
            />
            <span>{{ enfermedad.nombre }}</span>
        </div>
    </div>
</template>
