<template>
  <div class="mx-auto max-w-4xl py-10 sm:px-6 lg:px-8">
    <div class="card bg-base-100 border border-base-300 shadow-sm">
      <div class="card-body space-y-6">

        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-base-300 pb-5">
          <div>
            <h1 class="text-2xl font-bold">Evolución Clínica (SOAP)</h1>
            <p class="text-sm text-base-content/70">Complete la nota de evolución para cerrar la cita.</p>
          </div>
          <div class="text-right text-sm">
            <p class="font-semibold">
              {{ cita.paciente.nombre }} {{ cita.paciente.apellido_paterno }} {{ cita.paciente.apellido_materno }}
            </p>
            <p class="text-base-content/60">Dr(a). {{ cita.dentista.nombre }} {{ cita.dentista.apellido_paterno }}</p>
            <p class="text-base-content/50">{{ new Date(cita.fecha_inicio).toLocaleString('es-MX') }}</p>
          </div>
        </div>

        <form @submit.prevent="submit" class="space-y-6">

          <div class="grid gap-6 md:grid-cols-2">
            <div class="form-control">
              <label class="label">
                <span class="label-text font-medium">Subjetivo (S)</span>
              </label>
              <textarea v-model="form.subjetivo" rows="4" class="textarea textarea-bordered w-full" placeholder="Motivo de consulta, síntomas del paciente..." />
            </div>

            <div class="form-control">
              <label class="label">
                <span class="label-text font-medium">Objetivo (O)</span>
              </label>
              <textarea v-model="form.objetivo" rows="4" class="textarea textarea-bordered w-full" placeholder="Signos vitales, exploración física..." />
            </div>

            <div class="form-control">
              <label class="label">
                <span class="label-text font-medium">Análisis (A)</span>
              </label>
              <textarea v-model="form.analisis" rows="4" class="textarea textarea-bordered w-full" placeholder="Diagnóstico, evaluación del caso..." />
            </div>

            <div class="form-control">
              <label class="label">
                <span class="label-text font-medium">Plan (P)</span>
              </label>
              <textarea v-model="form.plan" rows="4" class="textarea textarea-bordered w-full" placeholder="Tratamiento a seguir, próximas citas..." />
            </div>
          </div>

          <div v-if="tratamientosPlanificados.length > 0" class="border-t border-base-300 pt-5">
            <h3 class="font-semibold mb-1">Tratamientos del Odontograma</h3>
            <p class="text-sm text-base-content/60 mb-4">Marque los tratamientos que fueron completados en esta cita.</p>
            <div class="space-y-2">
              <div v-for="t in tratamientosPlanificados" :key="t.id" class="flex items-center gap-3 rounded-box border border-base-300 p-3">
                <input :id="'t-' + t.id" :value="t.id" type="checkbox" class="checkbox checkbox-primary" v-model="form.tratamientos_completados" />
                <label :for="'t-' + t.id" class="flex-1 cursor-pointer">
                  <span class="font-medium">{{ t.nombre }}</span>
                  <span class="text-sm text-base-content/60 ml-2">{{ t.diente }} | Cara: {{ t.cara }}</span>
                </label>
              </div>
            </div>
          </div>

          <div class="border-t border-base-300 pt-5">
            <PrescriptionBuilder v-model="form.recetas" />
          </div>

          <div class="border-t border-base-300 pt-5 flex justify-end gap-3">
            <a :href="route('agenda.citas.confirmar', {cita: citaId})" class="btn btn-ghost">Cancelar</a>
            <button type="submit" :disabled="form.processing" class="btn btn-primary">
              Guardar y Cerrar Cita
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import PrescriptionBuilder from '@/Components/PrescriptionBuilder.vue';
import type { EvolutionNote } from '../../types';

const props = defineProps<{
  citaId: number;
  cita: {
    fecha_inicio: string;
    paciente: { nombre: string; apellido_paterno: string; apellido_materno: string | null };
    dentista: { nombre: string; apellido_paterno: string; apellido_materno: string | null };
  };
  tratamientosPlanificados: { id: number; nombre: string; diente: string; cara: string }[];
}>();

const form = useForm<EvolutionNote>({
  subjetivo: '',
  objetivo: '',
  analisis: '',
  plan: '',
  tratamientos_completados: [],
  recetas: [],
});

const submit = () => {
  form.post(route('evolucion.store', { cita: props.citaId }), {
    preserveScroll: true,
  });
};
</script>
