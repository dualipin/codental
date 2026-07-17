<script setup lang="ts">
import { useOdontogramaStore } from '@/stores/odontograma'
import type { CaraDental, EstadoHallazgo } from '@/types/Odontograma'

const props = defineProps<{
    tipo: 'inicial' | 'final'
}>()

const store = useOdontogramaStore()

const carasLabels: Record<CaraDental, string> = {
    V: 'Vestibular',
    L: 'Lingual/Palatino',
    M: 'Mesial',
    D: 'Distal',
    O: 'Oclusal/Incisal',
    C: 'Completo',
}

const estados: { valor: EstadoHallazgo; label: string }[] = [
    { valor: 'ACTIVO', label: 'Activo' },
    { valor: 'RESUELTO', label: 'Resuelto' },
    { valor: 'DESCARTADO', label: 'Descartado' },
]

function eliminar(diente: number, cara: CaraDental): void {
    store.eliminarHallazgo(props.tipo, diente, cara)
}
</script>

<template>
    <div class="overflow-x-auto">
        <table class="table table-zebra table-sm">
            <thead>
                <tr>
                    <th>Diente</th>
                    <th>Cara</th>
                    <th>Enfermedad</th>
                    <th>Notas</th>
                    <th>Estado</th>
                    <th v-if="tipo === 'final'">En plan</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="hallazgo in store.getHallazgosActivosPorTipo(tipo)"
                    :key="`${hallazgo.diente}-${hallazgo.cara}`"
                >
                    <td>{{ hallazgo.diente }}</td>
                    <td>{{ carasLabels[hallazgo.cara] }}</td>
                    <td>
                        {{ store.enfermedadesPorId.get(hallazgo.enfermedad_id)?.nombre }}
                    </td>
                    <td>
                        <input
                            v-model="hallazgo.notas"
                            type="text"
                            class="input input-sm w-full max-w-xs"
                            placeholder="Agregar nota"
                        />
                    </td>
                    <td>
                        <select v-model="hallazgo.estado" class="select select-sm">
                            <option
                                v-for="opcion in estados"
                                :key="opcion.valor"
                                :value="opcion.valor"
                            >
                                {{ opcion.label }}
                            </option>
                        </select>
                    </td>
                    <td v-if="tipo === 'final'">
                        <input
                            v-model="hallazgo.en_plan"
                            type="checkbox"
                            class="toggle toggle-primary toggle-sm"
                        />
                    </td>
                    <td>
                        <button
                            type="button"
                            class="btn btn-ghost btn-xs text-error"
                            @click="eliminar(hallazgo.diente, hallazgo.cara)"
                        >
                            Quitar
                        </button>
                    </td>
                </tr>

                <tr v-if="store.getHallazgosActivosPorTipo(tipo).length === 0">
                    <td
                        :colspan="tipo === 'final' ? 7 : 6"
                        class="text-center text-base-content/50 py-4"
                    >
                        No hay registros en este odontograma.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
