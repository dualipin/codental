<template>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Evolución Clínica (SOAP)</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Complete la nota de evolución y la receta para cerrar la cita.
                    </p>
                </div>
            </div>
            
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form @submit.prevent="submit">
                    <div class="shadow sm:rounded-md sm:overflow-hidden">
                        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                            
                            <!-- S: Subjetivo -->
                            <div>
                                <label for="subjetivo" class="block text-sm font-medium text-gray-700">Subjetivo (S)</label>
                                <div class="mt-1">
                                    <textarea id="subjetivo" v-model="form.subjetivo" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Motivo de consulta, síntomas del paciente..."></textarea>
                                </div>
                            </div>

                            <!-- O: Objetivo -->
                            <div>
                                <label for="objetivo" class="block text-sm font-medium text-gray-700">Objetivo (O)</label>
                                <div class="mt-1">
                                    <textarea id="objetivo" v-model="form.objetivo" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Signos vitales, exploración física..."></textarea>
                                </div>
                            </div>

                            <!-- A: Análisis -->
                            <div>
                                <label for="analisis" class="block text-sm font-medium text-gray-700">Análisis (A)</label>
                                <div class="mt-1">
                                    <textarea id="analisis" v-model="form.analisis" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Diagnóstico, evaluación del caso..."></textarea>
                                </div>
                            </div>

                            <!-- P: Plan -->
                            <div>
                                <label for="plan" class="block text-sm font-medium text-gray-700">Plan (P)</label>
                                <div class="mt-1">
                                    <textarea id="plan" v-model="form.plan" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Tratamiento a seguir, próximas citas..."></textarea>
                                </div>
                            </div>

                            <hr class="my-6">

                            <!-- Tratamientos Realizados -->
                            <div v-if="tratamientosPlanificados.length > 0">
                                <h4 class="text-md font-medium text-gray-900 mb-2">Tratamientos del Odontograma</h4>
                                <p class="text-sm text-gray-500 mb-4">Marque los tratamientos que fueron completados en esta cita.</p>
                                
                                <div class="space-y-2">
                                    <div v-for="tratamiento in tratamientosPlanificados" :key="tratamiento.id" class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input :id="'tratamiento-' + tratamiento.id" v-model="form.tratamientos_completados" :value="tratamiento.id" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label :for="'tratamiento-' + tratamiento.id" class="font-medium text-gray-700">{{ tratamiento.nombre }}</label>
                                            <p class="text-gray-500">Diente: {{ tratamiento.diente }} | Cara: {{ tratamiento.cara }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-6">

                            <!-- Receta Médica -->
                            <PrescriptionBuilder v-model="form.recetas" />

                        </div>
                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button type="submit" :disabled="form.processing" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                                Guardar y Cerrar Cita
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import PrescriptionBuilder from '@/Components/PrescriptionBuilder.vue';
import type { EvolutionNote } from '../../types';

const props = defineProps({
    citaId: {
        type: Number,
        required: true
    },
    tratamientosPlanificados: {
        type: Array as () => any[],
        default: () => []
    }
});

const form = useForm<EvolutionNote>({
    subjetivo: '',
    objetivo: '',
    analisis: '',
    plan: '',
    tratamientos_completados: [],
    recetas: []
});

const submit = () => {
    form.post(route('evolucion.store', { cita: props.citaId }), {
        preserveScroll: true,
        onSuccess: () => {
            // Manejar éxito, redirección manejada por el backend usualmente
        }
    });
};
</script>
