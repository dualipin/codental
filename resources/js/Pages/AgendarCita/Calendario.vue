<script setup lang="ts">
import {ref, computed} from 'vue'
import {Head, useForm} from '@inertiajs/vue3'
import {route} from 'ziggy-js'

import FullCalendar from '@fullcalendar/vue3'
import type {CalendarOptions, EventClickInfo, DateSelectInfo} from '@fullcalendar/vue3'
import themePlugin from '@fullcalendar/vue3/themes/monarch'
import dayGridPlugin from '@fullcalendar/vue3/daygrid'
import timeGridPlugin from '@fullcalendar/vue3/timegrid'
import interactionPlugin from '@fullcalendar/vue3/interaction'
import esLocale from '@fullcalendar/vue3/locales/es'

import '@fullcalendar/vue3/skeleton.css'
import '@fullcalendar/vue3/themes/monarch/theme.css'
import '@fullcalendar/vue3/themes/monarch/palettes/purple.css'

import type {Doctor} from '@/types/Doctor'

const props = defineProps<{
  paciente_id: number
  doctores: Doctor[]
  citas: Array<{
    id: number
    title: string
    start: string
    end: string
    backgroundColor: string
    borderColor: string
    display: string
    extendedProps: {
      dentista_id: number
    }
  }>
}>()

const dentistaId = ref<string>('')
const selectedStart = ref<string | null>(null)
const selectedEnd = ref<string | null>(null)

const form = useForm({
  paciente_id: props.paciente_id,
  dentista_id: 0,
  fecha_inicio: '',
  fecha_fin: '',
  motivo: '',
})

const citasFiltradas = computed(() => {
  if (!dentistaId.value) return []
  return props.citas.filter(
      (c) => c.extendedProps.dentista_id === Number(dentistaId.value)
  )
})

const calendarOptions = computed<CalendarOptions>(() => ({
  plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, themePlugin],
  initialView: 'timeGridWeek',
  locale: esLocale,
  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,timeGridWeek,timeGridDay',
  },
  allDaySlot: false,
  slotMinTime: '09:00:00',
  slotMaxTime: '18:00:00',
  slotDuration: '01:00:00',
  selectable: true,
  selectMirror: true,
  selectConstraint: 'businessHours',
  businessHours: {
    daysOfWeek: [1, 2, 3, 4, 5, 6],
    startTime: '09:00',
    endTime: '18:00',
  },
  weekends: true,
  firstDay: 1,
  height: 'auto',
  events: citasFiltradas.value as any,
  select: (info: DateSelectInfo) => {
    selectedStart.value = info.startStr
    selectedEnd.value = info.endStr
  },
  eventClick: (info: EventClickInfo) => {
    info.el.style.cursor = 'not-allowed'
  },
  selectAllow: (info) => {
    return info.start > new Date() && info.end.getTime() - info.start.getTime() <= 3600000
  },
}))

function confirmarCita() {
  if (!dentistaId.value || !selectedStart.value || !selectedEnd.value) return

  form.dentista_id = Number(dentistaId.value)
  form.fecha_inicio = selectedStart.value
  form.fecha_fin = selectedEnd.value

  form.post(route('agendar-cita.calendario.store'), {
    preserveScroll: true,
  })
}

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
</script>

<template>
  <Head title="Seleccionar Horario"/>

  <div class="min-h-screen bg-linear-to-b from-base-200 to-base-100">
    <div class="mx-auto max-w-6xl px-4 py-6">
      <div class="card border border-base-300 bg-base-100 shadow-xl">
        <div class="card-body gap-6">
          <div>
            <h1 class="text-2xl font-semibold text-primary">Selecciona tu horario</h1>
            <p class="mt-1 text-sm text-base-content/70">
              Elige un dentista y después da clic sobre un horario disponible en el calendario.
            </p>
          </div>

          <div v-if="form.recentlySuccessful" class="alert alert-success">
            Cita agendada correctamente. Serás redirigido al inicio.
          </div>

          <div v-if="(form.errors as any).horario" class="alert alert-error text-sm">
            {{ (form.errors as any).horario }}
          </div>

          <label class="form-control max-w-xl">
            <span class="label-text">Selecciona el dentista *</span>
            <select v-model="dentistaId" class="select select-bordered w-full" required>
              <option value="" disabled>Selecciona un dentista</option>
              <option
                  v-for="doctor in doctores"
                  :key="String(doctor.id)"
                  :value="String(doctor.id)"
              >
                Dr(a). {{ doctor.nombre }} {{ doctor.apellido_paterno }}
              </option>
            </select>
          </label>

          <label class="form-control max-w-xl">
            <span class="label-text">Motivo de la cita <span class="text-xs text-base-content/50">(opcional)</span></span>
            <textarea
              v-model="form.motivo"
              class="textarea textarea-bordered h-24 w-full"
              placeholder="Describe el motivo de tu consulta..."
              maxlength="500"
            />
          </label>

          <div v-if="dentistaId">
            <FullCalendar :options="calendarOptions"/>
          </div>

          <div v-else class="flex flex-col items-center justify-center py-16 text-base-content/50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="1">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-lg">Selecciona un dentista para ver su disponibilidad</p>
          </div>

          <div
              v-if="selectedStart && selectedEnd && dentistaId"
              class="rounded-box border border-primary/30 bg-primary/5 p-4"
          >
            <h3 class="font-semibold text-primary">Horario seleccionado</h3>
            <p class="mt-1 text-sm">
              {{ formatearFecha(selectedStart) }}
            </p>
            <button
                type="button"
                class="btn btn-primary mt-3"
                :disabled="form.processing"
                @click="confirmarCita"
            >
              <span v-if="form.processing" class="loading loading-spinner loading-sm"/>
              Confirmar y agendar cita
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
