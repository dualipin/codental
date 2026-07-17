<script setup lang="ts">
import {ref} from 'vue'
import {router} from '@inertiajs/vue3'
import {route} from 'ziggy-js'
import {Head} from '@inertiajs/vue3'
import axios from "axios";
import {toast} from "vue3-toastify";

interface Cita {
  id: number
  paciente_id: number
  dentista_id: number
  fecha_inicio: string
  fecha_fin: string
  motivo: string | null
  estatus: string
  paciente: {
    id: number
    nombre: string
    apellido_paterno: string
    apellido_materno: string
    telefono: string
    email: string | null
  }
  dentista: {
    id: number
    nombre: string
    apellido_paterno: string
    apellido_materno: string
    especialidad: string | null
  }
}

const props = defineProps<{
  cita: Cita
}>()

const loading = ref(false)

async function descargarPdf() {
  loading.value = true
  // router.get(route('agendar-cita.descargar-pdf', {cita: props.cita.id}), {
  //   preserveScroll: true,
  //   // @ts-ignore
  //   onFinish: () => {
  //     loading.value = false
  //   }
  // })

  try {
     const response = await axios.get(route('agendar-cita.descargar-pdf', {cita: props.cita.id}), {
      responseType: 'blob',
    })

    const fileURL = window.URL.createObjectURL(new Blob([response.data], { type: 'application/pdf' }))

    let fileName = `cita-${props.cita.id}.pdf`
    const contentDisposition = response.headers['content-disposition']
    if (contentDisposition) {
      const fileNameMatch = contentDisposition.match(/filename="?(.+)"?/)
      if (fileNameMatch.length === 2) {
        fileName = fileNameMatch[1]
      }
    }

    const fileLink = document.createElement('a')
    fileLink.href = fileURL
    fileLink.setAttribute('download', fileName)
    document.body.appendChild(fileLink)
    fileLink.click()
    fileLink.remove()

    window.URL.revokeObjectURL(fileURL)

  } catch (error) {
    console.log(error)

    toast.error("Ocurrió un error al descargar el PDF")

  } finally {
    loading.value = false
  }

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

function getEstatusColor(estatus: string): string {
  const colores: Record<string, string> = {
    PENDIENTE: 'bg-amber-100 text-amber-800',
    CONFIRMADA: 'bg-green-100 text-green-800',
    CANCELADA: 'bg-red-100 text-red-800',
    COMPLETADA: 'bg-blue-100 text-blue-800',
  }
  return colores[estatus] || 'bg-gray-100 text-gray-800'
}
</script>

<template>
  <Head :title="`Cita #${cita.id} - CoDental`"/>

  <div class="min-h-screen bg-base-200">
    <div class="mx-auto max-w-4xl px-4 py-8">
      <!-- Header -->
      <div class="mb-8 text-center">
        <div
            class="inline-flex items-center gap-2 bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-medium mb-4">
          <i class="bi bi-check-circle"></i>
          Cita Agendada Correctamente
        </div>
        <h1 class="text-3xl font-bold text-base-content">Comprobante de Cita</h1>
        <p class="mt-2 text-base-content/70">Guarde o descargue este comprobante para su cita</p>
      </div>

      <!-- Card Principal -->
      <div class="card bg-base-100 shadow-xl border border-base-300">
        <div class="card-body p-6 space-y-6">
          <!-- Folio y Estatus -->
          <div class="flex flex-wrap items-center justify-between gap-4 border-b border-base-300 pb-4">
            <div>
              <p class="text-sm text-base-content/50">Folio de la cita</p>
              <p class="text-2xl font-bold text-primary">#{{ cita.id }}</p>
            </div>
            <div class="flex items-center gap-2">
              <span
                  class="badge badge-lg"
                  :class="getEstatusColor(cita.estatus)"
              >
                {{ cita.estatus }}
              </span>
            </div>
          </div>

          <!-- Información de la Cita -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
              <h3 class="text-lg font-semibold text-base-content flex items-center gap-2">
                <i class="bi bi-clock text-primary"></i>
                Horario
              </h3>
              <div class="space-y-3 pl-7 border-l-2 border-primary/20">
                <div>
                  <p class="text-sm text-base-content/50">Inicio</p>
                  <p class="font-medium">{{ formatearFecha(cita.fecha_inicio) }}</p>
                </div>
                <div>
                  <p class="text-sm text-base-content/50">Fin</p>
                  <p class="font-medium">{{ formatearFecha(cita.fecha_fin) }}</p>
                </div>
                <div v-if="cita.motivo">
                  <p class="text-sm text-base-content/50">Motivo</p>
                  <p class="font-medium">{{ cita.motivo }}</p>
                </div>
              </div>
            </div>

            <div class="space-y-4">
              <h3 class="text-lg font-semibold text-base-content flex items-center gap-2">
                <i class="bi bi-person text-primary"></i>
                Paciente
              </h3>
              <div class="space-y-3 pl-7 border-l-2 border-primary/20">
                <div>
                  <p class="text-sm text-base-content/50">Nombre</p>
                  <p class="font-medium">{{ cita.paciente.nombre }} {{ cita.paciente.apellido_paterno }}
                    {{ cita.paciente.apellido_materno }}</p>
                </div>
                <div>
                  <p class="text-sm text-base-content/50">Teléfono</p>
                  <p class="font-medium">{{ cita.paciente.telefono }}</p>
                </div>
                <div v-if="cita.paciente.email">
                  <p class="text-sm text-base-content/50">Email</p>
                  <p class="font-medium">{{ cita.paciente.email }}</p>
                </div>
              </div>
            </div>

            <div class="space-y-4 md:col-span-2">
              <h3 class="text-lg font-semibold text-base-content flex items-center gap-2">
                <i class="bi bi-person text-primary"></i>
                Dentista Asignado
              </h3>
              <div class="space-y-3 pl-7 border-l-2 border-primary/20">
                <div>
                  <p class="text-sm text-base-content/50">Nombre</p>
                  <p class="font-medium">Dr(a). {{ cita.dentista.nombre }} {{ cita.dentista.apellido_paterno }}
                    {{ cita.dentista.apellido_materno }}</p>
                </div>
                <div v-if="cita.dentista.especialidad">
                  <p class="text-sm text-base-content/50">Especialidad</p>
                  <p class="font-medium">{{ cita.dentista.especialidad }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Acciones -->
          <div class="flex flex-wrap items-center justify-between gap-4 border-t border-base-300 pt-4">
            <div class="text-sm text-base-content/60">
              <p>Fecha de generación: {{ new Date().toLocaleString('es-MX') }}</p>
              <p>Guarde este comprobante para presentar el día de su cita.</p>
            </div>
            <div class="flex gap-3">
              <button
                  @click="descargarPdf"
                  :disabled="loading"
                  class="btn btn-primary gap-2"
              >
                <i v-if="loading" class="bi bi-arrow-repeat animate-spin"></i>
                <i v-else class="bi bi-download"></i>
                Descargar PDF
              </button>
              <a
                  :href="route('index')"
                  class="btn btn-ghost"
              >
                <i class="bi bi-chevron-left"></i>
                Volver al Inicio
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Nota informativa -->
      <div class="mt-6 p-4 bg-info/10 border border-info/30 rounded-lg">
        <div class="flex items-start gap-3">
          <i class="bi bi-info-circle text-accent"></i>
          <div>
            <p class="font-medium text-info-content">Importante:</p>
            <ul class="mt-2 text-sm text-info-content/90 space-y-1">
              <li>• Presente este comprobante (impreso o en su móvil) el día de su cita</li>
              <li>• Llegue 10 minutos antes de su horario programado</li>
              <li>• Si necesita cancelar o reprogramar, contacte a la clínica con al menos 2 horas de anticipación</li>
              <li>• Traiga su identificación oficial</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>