<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import RegistrarPacienteExpedienteWizard from '@/components/Pacientes/RegistrarPacienteExpedienteWizard.vue'
</script>

<template>
  <Head title="Nuevo paciente" />

  <section class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold">Registrar paciente</h1>
        <p class="text-sm text-base-content/70 mt-1">Completa el expediente en pasos para guardar paciente e historia clinica.</p>
      </div>
      <Link :href="route('pacientes.index')" class="btn btn-soft">Volver</Link>
    </div>

    <RegistrarPacienteExpedienteWizard />
  </section>
</template>
