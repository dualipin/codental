<script setup lang="ts">
import {Head} from "@inertiajs/vue3";
import {route} from "ziggy-js";
</script>

<template>
  <Head title="Agendar Cita"/>

  <div class="mt-3 flex justify-end gap-2 px-4 fixed top-0 inset-x-0 z-10">
    <a :href="route('index')" class="btn btn-secondary btn-sm">Inicio</a>
    <a :href="route('login.show')" class="btn btn-primary btn-sm"><i class="bi bi-box-arrow-in-right me-1"></i>
      Iniciar sesión
    </a>
  </div>

  <div class="hero bg-base-200 min-h-screen">
    <div class="hero-content text-center">
      <div class="absolute -z-10 flex inset-0 justify-center items-center">
        <div class="aspect-square rounded-full w-3/4 sm:w-1/2 bg-secondary/20 animate-pulse blur-3xl"></div>
      </div>
      <div class="max-w-md">
        <h1 class="text-secondary font-display font-semibold text-6xl">Bienvenido a la Agenda de Citas</h1>
        <p class="py-8 text-xl">
          ¿Es usted un paciente nuevo en la clínica?
        </p>
        <div class="d-md-flex justify-content-center gap-3">
          <button class="btn btn-primary btn-lg px-4 mb-2 mb-md-0" onclick="mostrarNuevo()"><i
              class="fa-solid fa-user-plus me-2"></i> Sí, soy paciente nuevo
          </button>
          <button class="btn btn-outline-secondary btn-lg px-4" onclick="mostrarRegistrado()"><i
              class="fa-solid fa-id-card me-2"></i> No, ya estoy registrado
          </button>
        </div>
      </div>
    </div>
  </div>
</template>