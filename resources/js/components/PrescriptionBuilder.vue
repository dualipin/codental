<template>
    <div class="space-y-4">
        <h3 class="text-lg font-medium text-gray-900">Receta Médica</h3>
        
        <div v-for="(item, index) in modelValue" :key="index" class="flex gap-4 items-end bg-gray-50 p-4 rounded-lg">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700">Medicamento</label>
                <input v-model="item.medicamento" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Ej. Amoxicilina 500mg" required />
            </div>
            
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700">Dosis</label>
                <input v-model="item.dosis" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Ej. 1 cápsula" required />
            </div>

            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700">Frecuencia</label>
                <input v-model="item.frecuencia" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Ej. Cada 8 horas" required />
            </div>

            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700">Duración</label>
                <input v-model="item.duracion" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Ej. 7 días" required />
            </div>

            <div>
                <button type="button" @click="removeItem(index)" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Eliminar
                </button>
            </div>
        </div>

        <button type="button" @click="addItem" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Añadir Medicamento
        </button>
    </div>
</template>

<script setup lang="ts">
import { PropType } from 'vue';
import type { PrescriptionItem } from '../types';

const props = defineProps({
    modelValue: {
        type: Array as PropType<PrescriptionItem[]>,
        required: true
    }
});

const emit = defineEmits(['update:modelValue']);

const addItem = () => {
    const newItem: PrescriptionItem = {
        medicamento: '',
        dosis: '',
        frecuencia: '',
        duracion: ''
    };
    emit('update:modelValue', [...props.modelValue, newItem]);
};

const removeItem = (index: number) => {
    const newValue = [...props.modelValue];
    newValue.splice(index, 1);
    emit('update:modelValue', newValue);
};
</script>
