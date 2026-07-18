<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

import FullCalendar from '@fullcalendar/vue3'
import type { CalendarOptions } from '@fullcalendar/vue3'
import themePlugin from '@fullcalendar/vue3/themes/monarch'
import dayGridPlugin from '@fullcalendar/vue3/daygrid'
import timeGridPlugin from '@fullcalendar/vue3/timegrid'
import interactionPlugin from '@fullcalendar/vue3/interaction'
import esLocale from '@fullcalendar/vue3/locales/es'
import type { DateSelectArg, EventClickArg } from '@fullcalendar/core'

import '@fullcalendar/vue3/skeleton.css'
import '@fullcalendar/vue3/themes/monarch/theme.css'
import '@fullcalendar/vue3/themes/monarch/palettes/purple.css'

type CitaEvento = {
  id: number
  estatus?: 'Pendiente' | 'Confirmada' | string
  title: string
  start: string
  end: string
  backgroundColor: string
  borderColor: string
  color?: string
  display: string
  extendedProps: {
    dentista_id: number
    dentista_nombre?: string
    paciente_id?: number
    paciente_nombre?: string
    paciente_apellido_paterno?: string
    paciente_apellido_materno?: string
    confirmacion_url?: string
  }
}

type Doctor = {
  id: number
  nombre: string
  apellido_paterno: string
  apellido_materno: string
}

type Paciente = {
  id: number
  nombre: string
  apellido_paterno: string
  apellido_materno: string
  telefono: string
}

type PacienteSeleccionado = Paciente | null

const props = defineProps<{
  rolUsuario: 'admin' | 'recep' | 'dent' | string
  usuarioId: number
  citas: CitaEvento[]
  doctores: Doctor[]
  pacientes: Paciente[]
  pacienteSeleccionado: PacienteSeleccionado
}>()

const filtroDentistaId = ref<string>('')
const selectedStart = ref<string | null>(null)
const selectedEnd = ref<string | null>(null)
const buscadorPaciente = ref(props.pacienteSeleccionado ? `${props.pacienteSeleccionado.nombre} ${props.pacienteSeleccionado.apellido_paterno} ${props.pacienteSeleccionado.apellido_materno}`.trim() : '')
const pacienteSeleccionadoId = ref<string>(props.pacienteSeleccionado ? String(props.pacienteSeleccionado.id) : '')
const mostrarResultadosPaciente = ref(false)

const esAdminORecepcionista = computed(() => ['admin', 'recep'].includes(props.rolUsuario))
const puedeElegirDentista = computed(() => esAdminORecepcionista.value)
const doctorFijoParaCreacion = computed<number | null>(() => {
  if (props.rolUsuario === 'dent') {
    return props.usuarioId
  }

  return filtroDentistaId.value ? Number(filtroDentistaId.value) : null
})

const mostrarSelectorDentista = computed(() => props.rolUsuario !== 'dent' && !filtroDentistaId.value)
const doctoresDisponibles = computed(() => (props.rolUsuario === 'dent' ? props.doctores.filter((doctor) => doctor.id === props.usuarioId) : props.doctores))

const form = useForm({
  paciente_id: props.pacienteSeleccionado ? String(props.pacienteSeleccionado.id) : '',
  dentista_id: props.rolUsuario === 'dent' ? String(props.usuarioId) : '',
  fecha_inicio: '',
  fecha_fin: '',
  motivo: '',
})

const pacientesFiltrados = computed(() => {
  const query = buscadorPaciente.value.trim().toLowerCase()

  if (!query) {
    return props.pacientes.slice(0, 8)
  }

  return props.pacientes
    .filter((paciente) => {
      const texto = [paciente.nombre, paciente.apellido_paterno, paciente.apellido_materno, paciente.telefono]
        .filter(Boolean)
        .join(' ')
        .toLowerCase()

      return texto.includes(query)
    })
    .slice(0, 10)
})

const pacienteSeleccionado = computed(() => {
  if (!pacienteSeleccionadoId.value) {
    return null
  }

  return props.pacientes.find((paciente) => String(paciente.id) === pacienteSeleccionadoId.value) ?? props.pacienteSeleccionado
})

const coloresPorEstatus = {
  Pendiente: { fondo: '#f59e0b', borde: '#d97706' },
  Confirmada: { fondo: '#22c55e', borde: '#16a34a' },
  Cancelada: { fondo: '#6b7280', borde: '#4b5563' },
} as const

const citasFiltradas = computed(() => {
  if (!esAdminORecepcionista.value) {
    return props.citas
  }

  if (!filtroDentistaId.value) {
    return props.citas
  }

  return props.citas.filter((cita) => cita.extendedProps.dentista_id === Number(filtroDentistaId.value))
})

const resumenVista = computed(() => {
  if (props.rolUsuario === 'dent') {
    return 'Viendo solo tus citas agendadas.'
  }

  if (!filtroDentistaId.value) {
    return 'Viendo citas de todos los medicos.'
  }

  const doctor = props.doctores.find((item) => item.id === Number(filtroDentistaId.value))

  if (!doctor) {
    return 'Viendo citas filtradas.'
  }

  return `Viendo citas de Dr(a). ${doctor.nombre} ${doctor.apellido_paterno}`
})

watch(filtroDentistaId, () => {
  selectedStart.value = null
  selectedEnd.value = null
  form.clearErrors()

  if (doctorFijoParaCreacion.value) {
    form.dentista_id = String(doctorFijoParaCreacion.value)
    return
  }

  if (props.rolUsuario !== 'dent') {
    form.dentista_id = ''
  }
})

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

function limpiarSeleccion() {
  selectedStart.value = null
  selectedEnd.value = null
  form.clearErrors()
}

function seleccionarPaciente(paciente: Paciente) {
  pacienteSeleccionadoId.value = String(paciente.id)
  buscadorPaciente.value = `${paciente.nombre} ${paciente.apellido_paterno} ${paciente.apellido_materno}`.trim()
  form.paciente_id = String(paciente.id)
  mostrarResultadosPaciente.value = false
}

function limpiarPaciente() {
  pacienteSeleccionadoId.value = ''
  buscadorPaciente.value = ''
  form.paciente_id = ''
}

function guardarCita() {
  if (!selectedStart.value || !selectedEnd.value) {
    return
  }

  if (doctorFijoParaCreacion.value) {
    form.dentista_id = String(doctorFijoParaCreacion.value)
  }

  if (!form.dentista_id) {
    return
  }

  form.fecha_inicio = selectedStart.value
  form.fecha_fin = selectedEnd.value

  form.post(route('agenda.citas.store'), {
    preserveScroll: true,
    onSuccess: () => {
      limpiarSeleccion()
      form.reset('paciente_id', 'motivo')
      if (props.rolUsuario !== 'dent') {
        form.dentista_id = ''
      }
    },
  })
}

function seleccionarHorario(info: DateSelectArg) {
  selectedStart.value = info.startStr
  selectedEnd.value = info.endStr
}

const calendarOptions = computed<CalendarOptions>(() => ({
  plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, themePlugin],
  initialView: 'timeGridWeek',
  locale: esLocale,
  timeZone: 'America/Mexico_City',
  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,timeGridWeek,timeGridDay',
  },
  allDaySlot: false,
  slotMinTime: '09:00:00',
  slotMaxTime: '18:00:00',
  slotDuration: '00:30:00',
  weekends: true,
  firstDay: 1,
  nowIndicator: true,
  height: 'auto',
  editable: false,
  selectable: true,
  selectMirror: true,
  unselectAuto: true,
  selectConstraint: 'businessHours',
  businessHours: {
    daysOfWeek: [1, 2, 3, 4, 5, 6],
    startTime: '09:00',
    endTime: '18:00',
  },
  selectAllow: (info) => info.start >= new Date(),
  events: citasFiltradas.value.map((cita) => {
    const color = coloresPorEstatus[cita.estatus as keyof typeof coloresPorEstatus] ?? coloresPorEstatus.Confirmada

    return {
      ...cita,
      color: color.fondo,
    }
  }) as any,
  select: seleccionarHorario,
  eventClick: (info: EventClickArg) => {
    const url = info.event.extendedProps.confirmacion_url as string | undefined

    if (url) {
      router.visit(url)
    }
  },
}))
</script>

<template>
  <Head title="Agenda" />

  <section class="space-y-5">
    <div class="space-y-1">
      <h1 class="text-2xl font-bold">Agenda de citas</h1>
      <p class="text-sm text-base-content/70">{{ resumenVista }}</p>
      <div v-if="pacienteSeleccionado" class="badge badge-primary badge-outline mt-1 max-w-full truncate">
        Paciente cargado: {{ pacienteSeleccionado.nombre }} {{ pacienteSeleccionado.apellido_paterno }}
      </div>
    </div>

    <div class="flex flex-wrap items-center gap-3 text-sm text-base-content/70">
      <span class="inline-flex items-center gap-2">
        <span class="h-3 w-3 rounded-full bg-amber-500"></span>
        Pendiente
      </span>
      <span class="inline-flex items-center gap-2">
        <span class="h-3 w-3 rounded-full bg-green-500"></span>
        Confirmada
      </span>
      <span class="inline-flex items-center gap-2">
        <span class="h-3 w-3 rounded-full bg-gray-500"></span>
        Cancelada
      </span>
    </div>

    <div v-if="esAdminORecepcionista" class="card bg-base-100 border border-base-300">
      <div class="card-body p-4">
        <label class="form-control max-w-sm">
          <span class="label-text">Filtrar por medico</span>
          <select v-model="filtroDentistaId" class="select select-bordered">
            <option value="">Todos los medicos</option>
            <option v-for="doctor in doctores" :key="doctor.id" :value="String(doctor.id)">
              Dr(a). {{ doctor.nombre }} {{ doctor.apellido_paterno }} {{ doctor.apellido_materno }}
            </option>
          </select>
        </label>
      </div>
    </div>

    <div class="card bg-base-100 border border-base-300 shadow-sm overflow-x-auto">
      <div class="card-body p-4 md:p-6 min-w-[640px]">
        <FullCalendar :options="calendarOptions" />
      </div>
    </div>

    <div class="rounded-box border border-primary/20 bg-primary/5 p-4 md:p-5 space-y-4">
      <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
          <h3 class="font-semibold text-primary">Registrar cita</h3>
          <p class="text-sm text-base-content/70">Selecciona un paciente y confirma el horario marcado en el calendario.</p>
        </div>
        <button v-if="selectedStart || selectedEnd" class="btn btn-ghost btn-sm" type="button" @click="limpiarSeleccion">
          Limpiar selección
        </button>
      </div>

      <div class="grid gap-4 md:grid-cols-2">
        <div class="form-control relative">
          <span class="label-text">Paciente</span>
          <input
            v-model="buscadorPaciente"
            type="text"
            class="input input-bordered w-full"
            placeholder="Buscar por nombre o teléfono"
            @focus="mostrarResultadosPaciente = true"
            @blur="setTimeout(() => { mostrarResultadosPaciente = false }, 150)"
          />

          <input v-model="form.paciente_id" type="hidden" />

          <div v-if="mostrarResultadosPaciente" class="absolute left-0 right-0 top-full z-20 mt-2 rounded-box border border-base-300 bg-base-100 shadow-lg">
            <button
              v-for="paciente in pacientesFiltrados"
              :key="paciente.id"
              type="button"
              class="flex w-full items-center justify-between gap-3 px-4 py-3 text-left hover:bg-base-200"
              @click="seleccionarPaciente(paciente)"
            >
              <span class="truncate min-w-0">
                {{ paciente.nombre }} {{ paciente.apellido_paterno }} {{ paciente.apellido_materno }}
              </span>
              <span class="text-xs text-base-content/50 shrink-0">{{ paciente.telefono }}</span>
            </button>

            <div v-if="pacientesFiltrados.length === 0" class="px-4 py-3 text-sm text-base-content/60">
              No se encontraron pacientes.
            </div>
          </div>

          <div v-if="pacienteSeleccionado" class="mt-2 flex items-center gap-2 text-xs text-base-content/60">
            <span>Seleccionado:</span>
            <span class="badge badge-outline">
              {{ pacienteSeleccionado.nombre }} {{ pacienteSeleccionado.apellido_paterno }}
            </span>
            <button class="btn btn-ghost btn-xs" type="button" @click="limpiarPaciente">Limpiar</button>
          </div>

          <span v-if="form.errors.paciente_id" class="mt-1 text-xs text-error">{{ form.errors.paciente_id }}</span>
        </div>

        <label v-if="mostrarSelectorDentista" class="form-control">
          <span class="label-text">Dentista</span>
          <select v-model="form.dentista_id" class="select select-bordered w-full">
            <option value="" disabled>Selecciona un dentista</option>
            <option v-for="doctor in doctoresDisponibles" :key="doctor.id" :value="String(doctor.id)">
              Dr(a). {{ doctor.nombre }} {{ doctor.apellido_paterno }} {{ doctor.apellido_materno }}
            </option>
          </select>
          <span class="mt-1 text-xs text-base-content/60">Admin y recepción pueden elegir el médico desde aquí.</span>
          <span v-if="form.errors.dentista_id" class="mt-1 text-xs text-error">{{ form.errors.dentista_id }}</span>
        </label>

        <div v-else class="form-control">
          <span class="label-text">Dentista</span>
          <div class="rounded-box border border-base-300 bg-base-100 px-3 py-2 text-sm">
            <template v-if="doctorFijoParaCreacion">
              Dr(a). {{ props.doctores.find((doctor) => doctor.id === doctorFijoParaCreacion)?.nombre }}
              {{ props.doctores.find((doctor) => doctor.id === doctorFijoParaCreacion)?.apellido_paterno }}
              {{ props.doctores.find((doctor) => doctor.id === doctorFijoParaCreacion)?.apellido_materno }}
            </template>
            <template v-else>
              Selecciona un dentista arriba para fijarlo en esta cita.
            </template>
          </div>
        </div>
      </div>

      <div v-if="selectedStart && selectedEnd" class="rounded-box border border-base-300 bg-base-100 p-4 flex flex-wrap items-center justify-between gap-4">
        <div>
          <p class="text-sm text-base-content/50">Horario seleccionado</p>
          <p class="font-medium">{{ formatearFecha(selectedStart) }}</p>
          <p class="text-sm text-base-content/60">Hasta {{ formatearFecha(selectedEnd) }}</p>
        </div>

        <div class="flex items-center gap-2">
          <button class="btn btn-ghost btn-sm" type="button" @click="limpiarSeleccion">Cambiar horario</button>
          <button class="btn btn-primary btn-sm" :disabled="form.processing" type="button" @click="guardarCita">
            <span v-if="form.processing" class="loading loading-spinner loading-sm"></span>
            Agendar cita
          </button>
        </div>
      </div>

      <div v-else class="rounded-box border border-dashed border-base-300 bg-base-100/60 p-4 text-sm text-base-content/60">
        Selecciona un bloque horario en el calendario para habilitar el guardado.
      </div>

      <label class="form-control">
        <span class="label-text">Motivo</span>
        <textarea v-model="form.motivo" class="textarea textarea-bordered min-h-24 w-full" maxlength="500"></textarea>
        <span v-if="form.errors.motivo" class="mt-1 text-xs text-error">{{ form.errors.motivo }}</span>
      </label>

      <div v-if="(form.errors as any).horario" class="alert alert-error text-sm">
        {{ (form.errors as any).horario }}
      </div>
    </div>
  </section>
</template>
