<script setup lang="ts">
import { computed, ref } from 'vue'
import { Head } from '@inertiajs/vue3'

import FullCalendar from '@fullcalendar/vue3'
import type { CalendarOptions } from '@fullcalendar/vue3'
import themePlugin from '@fullcalendar/vue3/themes/monarch'
import dayGridPlugin from '@fullcalendar/vue3/daygrid'
import timeGridPlugin from '@fullcalendar/vue3/timegrid'
import interactionPlugin from '@fullcalendar/vue3/interaction'
import esLocale from '@fullcalendar/vue3/locales/es'

import '@fullcalendar/vue3/skeleton.css'
import '@fullcalendar/vue3/themes/monarch/theme.css'
import '@fullcalendar/vue3/themes/monarch/palettes/purple.css'

type CitaEvento = {
  id: number
  title: string
  start: string
  end: string
  backgroundColor: string
  borderColor: string
  display: string
  extendedProps: {
    dentista_id: number
    dentista_nombre?: string
  }
}

type Doctor = {
  id: number
  nombre: string
  apellido_paterno: string
  apellido_materno: string
}

const props = defineProps<{
  rolUsuario: 'admin' | 'recep' | 'dent' | string
  usuarioId: number
  citas: CitaEvento[]
  doctores: Doctor[]
}>()

const filtroDentistaId = ref<string>('')

const esAdminORecepcionista = computed(() => ['admin', 'recep'].includes(props.rolUsuario))

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
  slotDuration: '00:30:00',
  weekends: true,
  firstDay: 1,
  nowIndicator: true,
  height: 'auto',
  editable: false,
  selectable: false,
  events: citasFiltradas.value as any,
}))
</script>

<template>
  <Head title="Agenda" />

  <section class="space-y-5">
    <div class="space-y-1">
      <h1 class="text-2xl font-bold">Agenda de citas</h1>
      <p class="text-sm text-base-content/70">{{ resumenVista }}</p>
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

    <div class="card bg-base-100 border border-base-300 shadow-sm">
      <div class="card-body p-4 md:p-6">
        <FullCalendar :options="calendarOptions" />
      </div>
    </div>
  </section>
</template>
