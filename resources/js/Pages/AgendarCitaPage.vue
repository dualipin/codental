<script setup lang="ts">
import {Head, Link} from "@inertiajs/vue3";
</script>

<template>
  <Head title="Agendar Cita"/>

  <div class="container mt-3 d-flex justify-content-end gap-2">
    <Link href="/" class="btn btn-secondary btn-sm">Inicio</Link>
    <Link href="/login" class="btn btn-primary btn-sm"><i class="fa-solid fa-right-to-bracket me-1"></i>
      Iniciar sesión
    </Link>
  </div>
</template>