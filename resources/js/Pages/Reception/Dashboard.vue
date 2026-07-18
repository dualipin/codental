<template>
    <div class="w-full py-6">
        <h1 class="mb-6 text-2xl font-semibold text-base-content">Seguimiento</h1>

        <div class="card bg-base-100 shadow-sm p-5">
            <div class="border-b border-base-300 pb-5">
                <h3 class="text-lg font-medium text-base-content">
                    Pacientes que requieren seguimiento
                </h3>
                <p class="mt-1 text-sm text-base-content/70">
                    Pacientes sin cita en los últimos 6 meses o con tratamientos pendientes sin cita programada.
                </p>
            </div>

            <ul role="list" class="divide-y divide-base-300">
                <li v-if="followUpPatients.length === 0" class="py-4">
                    <div role="alert" class="alert">
                        <span>No hay pacientes que requieran seguimiento en este momento.</span>
                    </div>
                </li>

                <li v-for="paciente in followUpPatients" :key="paciente.id" class="py-4 hover:bg-base-200/40">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div class="flex flex-col">
                            <p class="text-sm font-medium text-primary truncate">
                                {{ nombreCompleto(paciente) }}
                            </p>
                            <p class="mt-1 text-sm text-base-content/70">
                                Tel: {{ paciente.telefono || 'No registrado' }}
                            </p>
                            <p v-if="paciente.citas && paciente.citas.length > 0" class="mt-1 text-xs text-base-content/50">
                                Última cita: {{ new Date(paciente.citas[0].fecha_inicio).toLocaleDateString() }}
                            </p>
                        </div>

                        <div class="shrink-0">
                            <a
                                v-if="puedeContactarPorWhatsapp(paciente)"
                                :href="whatsappLink(paciente)"
                                target="_blank"
                                rel="noreferrer"
                                class="btn btn-primary"
                            >
                                Contactar
                            </a>
                            <span v-else class="btn btn-disabled" aria-disabled="true">
                                Contactar
                            </span>
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

const nombreCompleto = (paciente: any) => {
    return [paciente.nombre, paciente.apellido_paterno, paciente.apellido_materno]
        .filter(Boolean)
        .join(' ');
};

const whatsappLink = (paciente: any) => {
    const telefono = String(paciente.telefono ?? '').replace(/\D/g, '');
    const telefonoConPrefijo = telefono.length === 10 ? `52${telefono}` : telefono;
    const mensaje = encodeURIComponent(
        `Hola ${nombreCompleto(paciente)}, le contactamos de la clínica para darle seguimiento a su atención. ¿Nos puede confirmar un horario para llamarle o agendar su próxima cita?`
    );

    return `https://wa.me/${telefonoConPrefijo}?text=${mensaje}`;
};

const puedeContactarPorWhatsapp = (paciente: any) => {
    const telefono = String(paciente.telefono ?? '').replace(/\D/g, '');
    return telefono.length > 0;
};
</script>
