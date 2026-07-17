@extends('layouts.app')

@section('app-content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Paciente</h1>
        <div class="flex gap-2">
            <a href="{{ route('pacientes.edit', $paciente) }}" class="btn btn-warning">Editar</a>
            <a href="{{ route('pacientes.index') }}" class="btn btn-soft">Volver</a>
        </div>
    </div>

    <div class="card p-6 max-w-4xl">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <span class="label">Nombre completo</span>
                <p class="text-lg font-semibold">{{ $paciente->nombre }} {{ $paciente->apellido_paterno }} {{ $paciente->apellido_materno }}</p>
            </div>

            <div>
                <span class="label">Teléfono</span>
                <p class="text-lg">{{ $paciente->telefono }}</p>
            </div>

            <div>
                <span class="label">Correo electrónico</span>
                <p class="text-lg">{{ $paciente->correo_electronico ?? '—' }}</p>
            </div>

            <div>
                <span class="label">Sexo</span>
                <p class="text-lg">
                    @switch($paciente->sexo->value)
                        @case('M') Masculino @break
                        @case('F') Femenino @break
                        @case('O') Otro @break
                    @endswitch
                </p>
            </div>

            <div>
                <span class="label">Fecha de nacimiento</span>
                <p class="text-lg">{{ $paciente->fecha_nacimiento->format('d/m/Y') }}</p>
            </div>

            <div>
                <span class="label">Estado civil</span>
                <p class="text-lg">{{ $paciente->estado_civil ?? '—' }}</p>
            </div>

            <div>
                <span class="label">Ocupación</span>
                <p class="text-lg">{{ $paciente->ocupacion ?? '—' }}</p>
            </div>

            <div>
                <span class="label">Religión</span>
                <p class="text-lg">{{ $paciente->religion ?? '—' }}</p>
            </div>

            <div class="md:col-span-2">
                <span class="label">Dirección</span>
                <p class="text-lg">{{ $paciente->direccion ?? '—' }}</p>
            </div>

            <div>
                <span class="label">Estado</span>
                <p class="text-lg">{{ $paciente->estado ?? '—' }}</p>
            </div>

            <div>
                <span class="label">Municipio</span>
                <p class="text-lg">{{ $paciente->municipio ?? '—' }}</p>
            </div>
        </div>
    </div>
@endsection