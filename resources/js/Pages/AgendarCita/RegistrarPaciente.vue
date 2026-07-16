<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import RegistrarPacienteWizard from '@/components/AgendarCita/RegistrarPacienteWizard/RegistrarPacienteWizard.vue'
import {Doctor} from "@/types/Doctor";

defineProps<{
	doctores: Doctor[]
  formulario_publico: boolean
}>()
</script>

<template>
	<Head title="Registrar Paciente" />
	<RegistrarPacienteWizard :doctores="doctores" :formulario-publico="formulario_publico" />
</template>