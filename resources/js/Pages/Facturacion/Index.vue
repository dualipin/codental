<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import { Head, router, useForm, usePage } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

type Paciente = {
  id: number
  nombre: string
  apellido_paterno: string
  apellido_materno: string | null
  telefono: string | null
}

type Tratamiento = {
  id: number
  precio_congelado: number
  monto_descuento: number
  tratamientoCatalogo?: { nombre: string | null } | null
  distribuciones: Array<{ monto_aplicado: number }>
}

type Movimiento = {
  id: number
  fecha_hora: string | null
  created_at: string | null
  tipo_movimiento: string
  monto: number
  metodo_pago: string
  usuario?: { nombre: string | null } | null
  distribuciones: Array<{
    monto_aplicado: number
    presupuestoDetalle?: { tratamientoCatalogo?: { nombre: string | null } | null } | null
  }>
}

const props = defineProps<{
  rol: string | null
  puedeRegistrar: boolean
  pacientes: Paciente[]
  busqueda: string
  paciente: Paciente | null
  saldo: number
  total_cargos: number
  total_abonos: number
  tratamientos: Tratamiento[]
  movimientos: Movimiento[]
  idPac?: number | string | null
}>()

const busqueda = ref(props.busqueda ?? '')
const page = usePage<{ flash?: { success?: string } }>()
const pagoForm = useForm({
  paciente_id: props.paciente?.id ? String(props.paciente.id) : '',
  monto: '',
  metodo_pago: 'efectivo',
  referencia_bancaria: '',
  distribucion: [{ presupuesto_detalle_id: '', monto_aplicado: '' }],
})

watch(
  () => props.paciente,
  (paciente) => {
    pagoForm.paciente_id = paciente ? String(paciente.id) : ''
  },
  { immediate: true },
)

let debounce: ReturnType<typeof setTimeout> | null = null

watch(busqueda, (valor) => {
  if (debounce) {
    clearTimeout(debounce)
  }

  debounce = setTimeout(() => {
    router.get(route('caja.facturacion'), { q: valor || undefined }, {
      preserveScroll: true,
      replace: true,
    })
  }, 250)
})

const nombreCompleto = (paciente: Paciente) =>
  [paciente.nombre, paciente.apellido_paterno, paciente.apellido_materno].filter(Boolean).join(' ')

const pacientesFiltrados = computed(() => props.pacientes)
const mostrarResultados = computed(() => busqueda.value.trim().length > 0)

function seleccionarPaciente(paciente: Paciente) {
  router.get(route('caja.facturacion'), { id_pac: paciente.id, q: busqueda.value || undefined }, {
    preserveScroll: true,
    preserveState: true,
    replace: true,
  })
}

function limpiarBusqueda() {
  router.get(route('caja.facturacion'), {}, {
    preserveScroll: true,
    replace: true,
  })
}

function registrarAbono() {
  if (!props.paciente) return

  pagoForm.paciente_id = String(props.paciente.id)
  pagoForm.post(route('caja.abonos.store'), {
    preserveScroll: true,
    onSuccess: () => {
      pagoForm.reset('monto', 'referencia_bancaria')
      pagoForm.distribucion = [{ presupuesto_detalle_id: '', monto_aplicado: '' }]
    },
  })
}

function formatearFecha(fecha: string | null): string {
  return fecha ? new Date(fecha).toLocaleString('es-MX', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
  }) : '—'
}

function formatoMoneda(valor: number): string {
  return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(valor)
}
</script>

<template>
  <Head title="Facturación" />

  <section class="space-y-6">
    <div class="card border border-base-300 bg-base-100 shadow-sm">
      <div class="card-body gap-4">
        <div class="flex flex-col gap-2 lg:flex-row lg:items-end lg:justify-between">
          <div>
            <h1 class="card-title text-2xl">Facturación</h1>
            <p class="text-sm opacity-70">Ledger inmutable de cobros, anulación por reverso y trazabilidad por tratamiento.</p>
          </div>
          <div class="stats shadow">
            <div class="stat">
              <div class="stat-title">Saldo pendiente</div>
              <div class="stat-value text-error">{{ formatoMoneda(saldo) }}</div>
            </div>
          </div>
        </div>

        <div v-if="page.props.flash?.success" class="alert alert-success">
          <span>{{ page.props.flash.success }}</span>
        </div>

        <div class="grid gap-3 lg:grid-cols-4">
          <div class="form-control lg:col-span-3">
            <div class="label"><span class="label-text">Buscar paciente</span></div>
            <input
              v-model="busqueda"
              type="text"
              class="input"
              placeholder="Nombre, apellido o teléfono"
              autocomplete="off"
            />
          </div>
          <div class="flex items-end">
            <button class="btn btn-block" type="button" @click="limpiarBusqueda">Limpiar</button>
          </div>
        </div>

        <div v-if="mostrarResultados" class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
          <button
            v-for="item in pacientesFiltrados"
            :key="item.id"
            type="button"
            class="card bg-base-200 text-left transition-colors hover:bg-base-300"
            @click="seleccionarPaciente(item)"
          >
            <div class="card-body p-4">
              <div class="flex items-start justify-between gap-3">
                <div>
                  <h3 class="font-semibold">{{ nombreCompleto(item) }}</h3>
                  <p class="text-sm opacity-70">{{ item.telefono ?? '—' }}</p>
                </div>
                <span class="badge badge-soft">ID {{ item.id }}</span>
              </div>
            </div>
          </button>

          <div v-if="pacientesFiltrados.length === 0" class="alert alert-warning md:col-span-2 xl:col-span-3">
            <span>No se encontraron pacientes con esa búsqueda.</span>
          </div>
        </div>

        <div v-else class="alert alert-info">
          <span>Escribe un nombre, apellido o teléfono para buscar pacientes.</span>
        </div>
      </div>
    </div>

    <div v-if="paciente" class="grid gap-6 lg:grid-cols-3">
      <div class="card bg-base-100 border border-base-300 shadow-sm lg:col-span-1">
        <div class="card-body">
          <h2 class="card-title">Resumen</h2>
          <div class="space-y-2 text-sm">
            <div class="flex justify-between"><span>Cargos</span><strong>{{ formatoMoneda(total_cargos) }}</strong></div>
            <div class="flex justify-between"><span>Abonos</span><strong>{{ formatoMoneda(total_abonos) }}</strong></div>
            <div class="flex justify-between"><span>Saldo</span><strong class="text-error">{{ formatoMoneda(saldo) }}</strong></div>
          </div>
        </div>
      </div>

      <div class="card bg-base-100 border border-base-300 shadow-sm lg:col-span-2">
        <div class="card-body">
          <h2 class="card-title">Tratamientos aprobados</h2>
          <div class="overflow-x-auto">
            <table class="table table-zebra table-sm">
              <thead>
                <tr>
                  <th>Tratamiento</th>
                  <th>Precio</th>
                  <th>Descuento</th>
                  <th>Pagado</th>
                  <th>Pendiente</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="detalle in tratamientos" :key="detalle.id">
                  <td>{{ detalle.tratamientoCatalogo?.nombre ?? '—' }}</td>
                  <td>{{ formatoMoneda(detalle.precio_congelado) }}</td>
                  <td>{{ formatoMoneda(detalle.monto_descuento) }}</td>
                  <td>{{ formatoMoneda(detalle.distribuciones.reduce((sum, item) => sum + Number(item.monto_aplicado), 0)) }}</td>
                  <td>{{ formatoMoneda(Math.max(0, (detalle.precio_congelado - detalle.monto_descuento) - detalle.distribuciones.reduce((sum, item) => sum + Number(item.monto_aplicado), 0))) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="grid gap-6 lg:grid-cols-3 lg:col-span-3">
        <div class="card bg-base-100 shadow-sm lg:col-span-1">
          <div class="card-body">
            <h2 class="card-title">Registrar pago</h2>
            <form class="space-y-3" @submit.prevent="registrarAbono">
              <input type="hidden" v-model="pagoForm.paciente_id" />

              <label class="form-control">
                <div class="label"><span class="label-text">Detalle a cubrir</span></div>
                <select v-model="pagoForm.distribucion[0].presupuesto_detalle_id" class="select w-full" required>
                  <option value="" disabled>Selecciona un tratamiento</option>
                  <option v-for="detalle in tratamientos" :key="detalle.id" :value="String(detalle.id)">
                    {{ detalle.tratamientoCatalogo?.nombre ?? 'Tratamiento' }}
                  </option>
                </select>
              </label>

              <p class="text-xs opacity-70">El monto aplicado debe coincidir con el monto total del pago.</p>

              <label class="form-control">
                <div class="label"><span class="label-text">Monto del pago</span></div>
                <input v-model="pagoForm.monto" type="number" min="0.01" step="0.01" class="input w-full" required />
              </label>

              <label class="form-control">
                <div class="label"><span class="label-text">Monto aplicado al tratamiento</span></div>
                <input v-model="pagoForm.distribucion[0].monto_aplicado" type="number" min="0.01" step="0.01" class="input w-full" required />
              </label>

              <label class="form-control">
                <div class="label"><span class="label-text">Método de pago</span></div>
                <select v-model="pagoForm.metodo_pago" class="select w-full" required>
                  <option value="efectivo">Efectivo</option>
                  <option value="tarjeta">Tarjeta</option>
                  <option value="transferencia">Transferencia</option>
                  <option value="mixto">Mixto</option>
                </select>
              </label>

              <label class="form-control">
                <div class="label"><span class="label-text">Referencia bancaria</span></div>
                <input v-model="pagoForm.referencia_bancaria" type="text" class="input w-full" />
              </label>

              <label class="form-control">
                <div class="label"><span class="label-text">Observación</span></div>
                <textarea v-model="pagoForm.observacion" class="textarea w-full" rows="3" placeholder="Opcional"></textarea>
              </label>

              <button class="btn btn-primary btn-block" type="submit" :disabled="pagoForm.processing">
                <span v-if="pagoForm.processing" class="loading loading-spinner loading-sm"></span>
                Registrar pago
              </button>
            </form>
          </div>
        </div>

        <div class="card bg-base-100 shadow-sm lg:col-span-2">
          <div class="card-body">
            <h2 class="card-title">Ledger financiero</h2>
            <div class="overflow-x-auto">
              <table class="table table-zebra table-sm">
                <thead>
                  <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Monto</th>
                    <th>Método</th>
                    <th>Usuario</th>
                    <th>Detalle</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="movimiento in movimientos" :key="movimiento.id">
                    <td>{{ formatearFecha(movimiento.fecha_hora ?? movimiento.created_at) }}</td>
                    <td>{{ movimiento.tipo_movimiento }}</td>
                    <td>{{ formatoMoneda(movimiento.monto) }}</td>
                    <td>{{ movimiento.metodo_pago }}</td>
                    <td>{{ movimiento.usuario?.nombre ?? '—' }}</td>
                    <td>
                      <ul v-if="movimiento.distribuciones.length" class="text-xs space-y-1">
                        <li v-for="(distribucion, index) in movimiento.distribuciones" :key="index">
                          {{ distribucion.presupuestoDetalle?.tratamientoCatalogo?.nombre ?? 'Tratamiento' }}: {{ formatoMoneda(distribucion.monto_aplicado) }}
                        </li>
                      </ul>
                      <span v-else>—</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="alert alert-info">
      <span>No hay pacientes disponibles para facturación.</span>
    </div>
  </section>
</template>
