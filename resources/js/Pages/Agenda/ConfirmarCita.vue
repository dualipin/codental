<script setup lang="ts">
import {computed, ref} from 'vue'
import {Head, router} from '@inertiajs/vue3'
import {route} from 'ziggy-js'

type Cita = {
  id: number
  fecha_inicio: string
  fecha_fin: string
  motivo: string | null
  estatus: string
  paciente: {
    id: number
    nombre: string
    apellido_paterno: string
    apellido_materno: string | null
    telefono: string
    correo_electronico: string | null
    fecha_nacimiento: string | null
    sexo: string | null
    direccion: string | null
    estado: string | null
    municipio: string | null
    ocupacion: string | null
    estado_civil: string | null
    religion: string | null
    verificado: boolean
    historiaClinica: Record<string, any> | null
  }
  dentista: {
    id: number
    nombre: string
    apellido_paterno: string
    apellido_materno: string | null
    especialidad: string | null
  }
}

const props = defineProps<{
  cita: Cita
}>()

const processing = ref(false)

const coloresPorEstatus: Record<string, { fondo: string; texto: string }> = {
  Pendiente: { fondo: 'badge-warning', texto: 'Pendiente' },
  Confirmada: { fondo: 'badge-success', texto: 'Confirmada' },
  Cancelada: { fondo: 'badge-neutral', texto: 'Cancelada' },
}

const badgeEstatus = computed(() => coloresPorEstatus[props.cita.estatus] ?? { fondo: 'badge-ghost', texto: props.cita.estatus })

const nombreCompleto = computed(() => [
  props.cita.paciente.nombre,
  props.cita.paciente.apellido_paterno,
  props.cita.paciente.apellido_materno,
].filter(Boolean).join(' '))

function formatearFecha(iso: string): string {
  return new Date(iso).toLocaleString('es-MX', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

function confirmar() {
  processing.value = true
  router.patch(route('agenda.citas.confirmar.update', {cita: props.cita.id}), {}, {
    preserveScroll: true,
    onFinish: () => {
      processing.value = false
    },
  })
}

function cancelar() {
  processing.value = true
  router.patch(route('agenda.citas.cancelar', {cita: props.cita.id}), {}, {
    preserveScroll: true,
    onFinish: () => {
      processing.value = false
    },
  })
}
</script>

<template>
  <Head :title="`Cita #${cita.id}`"/>

  <div class="min-h-screen bg-base-200">
    <div class="mx-auto max-w-6xl px-4 py-6 space-y-6">
      <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
          <h1 class="text-3xl font-bold">Confirmar cita</h1>
          <p class="text-sm text-base-content/70">Revisa el expediente y decide si la cita se confirma o se cancela.</p>
        </div>
        <div class="flex gap-2">
          <button class="btn btn-success" :disabled="processing" @click="confirmar">Confirmar</button>
          <button class="btn btn-error" :disabled="processing" @click="cancelar">Cancelar</button>
          <a :href="route('pacientes.show', {paciente: props.cita.paciente.id})" class="btn btn-ghost">Ver expediente</a>
        </div>
      </div>

      <div class="grid gap-6 lg:grid-cols-3">
        <div class="card bg-base-100 border border-base-300 shadow-sm lg:col-span-1">
          <div class="card-body space-y-4">
            <div>
              <p class="text-sm text-base-content/50">Paciente</p>
              <h2 class="text-2xl font-semibold">{{ nombreCompleto }}</h2>
              <p class="text-sm text-base-content/60">{{ cita.paciente.telefono }}</p>
            </div>
            <div>
              <p class="text-sm text-base-content/50">Cita</p>
              <p class="font-medium">{{ formatearFecha(cita.fecha_inicio) }}</p>
              <p class="text-sm text-base-content/60">Hasta {{ formatearFecha(cita.fecha_fin) }}</p>
            </div>
            <div>
              <p class="text-sm text-base-content/50">Estatus</p>
              <span class="badge badge-lg" :class="badgeEstatus.fondo">{{ badgeEstatus.texto }}</span>
            </div>
            <div v-if="cita.motivo">
              <p class="text-sm text-base-content/50">Motivo</p>
              <p>{{ cita.motivo }}</p>
            </div>
            <div>
              <p class="text-sm text-base-content/50">Dentista</p>
              <p class="font-medium">Dr(a). {{ cita.dentista.nombre }} {{ cita.dentista.apellido_paterno }}</p>
              <p class="text-sm text-base-content/60">{{ cita.dentista.especialidad ?? 'Sin especialidad' }}</p>
            </div>
          </div>
        </div>

        <div class="card bg-base-100 border border-base-300 shadow-sm lg:col-span-2">
          <div class="card-body space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
              <h2 class="text-xl font-semibold">Expediente del paciente</h2>
              <span class="badge badge-outline">{{ cita.paciente.verificado ? 'Verificado' : 'No verificado' }}</span>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
              <div class="rounded-box border border-base-300 p-4">
                <p class="text-sm text-base-content/50">Datos generales</p>
                <div class="mt-3 space-y-2 text-sm">
                  <p><span class="font-medium">Nombre:</span> {{ nombreCompleto }}</p>
                  <p><span class="font-medium">Correo:</span> {{ cita.paciente.correo_electronico ?? '—' }}</p>
                  <p><span class="font-medium">Nacimiento:</span> {{ cita.paciente.fecha_nacimiento ?? '—' }}</p>
                  <p><span class="font-medium">Sexo:</span> {{ cita.paciente.sexo ?? '—' }}</p>
                  <p><span class="font-medium">Dirección:</span> {{ cita.paciente.direccion ?? '—' }}</p>
                </div>
              </div>

              <div class="rounded-box border border-base-300 p-4">
                <p class="text-sm text-base-content/50">Historia clínica</p>
                <div class="mt-3 space-y-2 text-sm">
                  <p><span class="font-medium">Alergias:</span> {{ cita.paciente.historiaClinica?.alergias ?? '—' }}</p>
                  <p><span class="font-medium">Medicacion:</span>
                    {{ cita.paciente.historiaClinica?.medicacion_actual ?? '—' }}</p>
                  <p><span class="font-medium">Padecimiento actual:</span>
                    {{ cita.paciente.historiaClinica?.padecimiento_actual ?? '—' }}</p>
                  <p><span class="font-medium">Grupo sanguíneo:</span>
                    {{ cita.paciente.historiaClinica?.grupo_sanguineo ?? '—' }}</p>
                </div>
              </div>
            </div>

            <div class="rounded-box border border-base-300 p-4">
              <div class="flex items-center justify-between gap-2">
                <p class="text-sm text-base-content/50">Acciones</p>
                <a :href="route('pacientes.show', {paciente: props.cita.paciente.id})" class="btn btn-ghost btn-sm">Abrir
                  expediente completo</a>
              </div>
              <p class="mt-2 text-sm text-base-content/70">Desde aquí puedes confirmar o cancelar la cita y revisar la
                información básica antes de entrar a la consulta.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
