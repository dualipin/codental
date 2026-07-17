<template>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Seguimiento</h1>
        
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Pacientes que requieren seguimiento
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Pacientes sin cita en los últimos 6 meses o con tratamientos pendientes sin cita programada.
                </p>
            </div>
            
            <ul role="list" class="divide-y divide-gray-200">
                <li v-if="followUpPatients.length === 0" class="px-4 py-4 sm:px-6 text-sm text-gray-500">
                    No hay pacientes que requieran seguimiento en este momento.
                </li>
                
                <li v-for="paciente in followUpPatients" :key="paciente.id" class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col">
                            <p class="text-sm font-medium text-indigo-600 truncate">
                                {{ paciente.nombre }} {{ paciente.apellido }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                Tel: {{ paciente.telefono || 'No registrado' }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1" v-if="paciente.citas && paciente.citas.length > 0">
                                Última cita: {{ new Date(paciente.citas[0].fecha_inicio).toLocaleDateString() }}
                            </p>
                        </div>
                        <div class="shrink-0">
                            <!-- TODO: Conectar con el modal de agendar cita de la Fase 2 -->
                            <a :href="route('agendar-cita') + '?paciente=' + paciente.id" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Llamar y Agendar
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup lang="ts">
import { PropType } from 'vue';

defineProps({
    followUpPatients: {
        type: Array as PropType<any[]>,
        required: true
    }
});
</script>
