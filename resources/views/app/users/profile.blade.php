@extends('layouts.app')

@section('app-content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Perfil de usuario</h1>
        <a href="{{ route('usuarios') }}" class="btn btn-soft">Volver</a>
    </div>

    <div class="card bg-base-200 p-6 max-w-2xl">
        <div class="flex items-center gap-4 mb-6">
            @if ($usuario->foto_usuario)
                <div class="avatar">
                    <div class="w-20 rounded-full">
                        <img src="{{ Storage::url($usuario->foto_usuario) }}" alt="Foto">
                    </div>
                </div>
            @else
                <div class="avatar placeholder">
                    <div class="bg-primary text-primary-content w-20 rounded-full">
                        <span class="text-3xl">{{ substr($usuario->nombre, 0, 1) }}{{ substr($usuario->apellido_paterno, 0, 1) }}</span>
                    </div>
                </div>
            @endif
            <div>
                <h2 class="text-xl font-bold">{{ $usuario->nombre }} {{ $usuario->apellido_paterno }} {{ $usuario->apellido_materno }}</h2>
                <span class="badge badge-soft badge-primary">{{ $usuario->rol->value }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <span class="text-sm opacity-60">Email</span>
                <p class="font-medium">{{ $usuario->email }}</p>
            </div>

            <div>
                <span class="text-sm opacity-60">Teléfono</span>
                <p class="font-medium">{{ $usuario->telefono }}</p>
            </div>

            <div>
                <span class="text-sm opacity-60">Sexo</span>
                <p class="font-medium">{{ $usuario->sexo->value }}</p>
            </div>

            <div>
                <span class="text-sm opacity-60">Fecha de nacimiento</span>
                <p class="font-medium">{{ $usuario->fecha_nacimiento?->format('d/m/Y') }}</p>
            </div>

            @if ($usuario->especialidad)
                <div>
                    <span class="text-sm opacity-60">Especialidad</span>
                    <p class="font-medium">{{ $usuario->especialidad }}</p>
                </div>
            @endif

            @if ($usuario->estado_civil)
                <div>
                    <span class="text-sm opacity-60">Estado civil</span>
                    <p class="font-medium">{{ $usuario->estado_civil }}</p>
                </div>
            @endif

            @if ($usuario->direccion)
                <div class="md:col-span-2">
                    <span class="text-sm opacity-60">Dirección</span>
                    <p class="font-medium">{{ $usuario->direccion }}</p>
                </div>
            @endif

            @if ($usuario->cedula)
                <div>
                    <span class="text-sm opacity-60">Cédula profesional</span>
                    <p class="font-medium">{{ $usuario->cedula }}</p>
                </div>
            @endif
        </div>

        <div class="mt-6 flex gap-2">
            <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-warning">Editar perfil</a>
        </div>
    </div>
@endsection
