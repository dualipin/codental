@extends('layouts.agenda')
@section('agenda-content')
    <div class="hero bg-base-200 min-h-screen -mt-14">
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
                    <a href="{{ route('agendar-cita.paciente-nuevo') }}"
                       class="btn btn-primary btn-lg px-4 mb-2 mb-md-0"><i
                                class="fa-solid fa-user-plus me-2"></i> Sí, soy paciente nuevo
                    </a>
                    <a href="{{ route('agendar-cita.identificar-paciente') }}"
                       class="btn btn-outline-secondary btn-lg px-4"><i
                                class="fa-solid fa-id-card me-2"></i> No, ya estoy registrado
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection