@extends('layouts.app')

@section('app-content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Pacientes</h1>
        <a href="{{ route('pacientes.create') }}" class="btn btn-primary">Nuevo paciente</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success mb-4">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <form method="GET" class="flex gap-2 mb-4">
        <input type="text" name="q" class="input input-bordered w-full max-w-xs" placeholder="Buscar por nombre o teléfono..." value="{{ $query ?? '' }}">
        <button class="btn btn-soft">Buscar</button>
        @if ($query)
            <a href="{{ route('pacientes.index') }}" class="btn btn-ghost">Limpiar</a>
        @endif
    </form>

    <div class="overflow-x-auto">
        <table class="table table-zebra">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Sexo</th>
                    <th>Fecha de nacimiento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pacientes as $paciente)
                    <tr>
                        <td>{{ $paciente->nombre }} {{ $paciente->apellido_paterno }} {{ $paciente->apellido_materno }}</td>
                        <td>{{ $paciente->telefono }}</td>
                        <td>
                            @switch($paciente->sexo->value)
                                @case('M') Masculino @break
                                @case('F') Femenino @break
                                @case('O') Otro @break
                            @endswitch
                        </td>
                        <td>{{ $paciente->fecha_nacimiento->format('d/m/Y') }}</td>
                        <td class="flex gap-1">
                            <a href="{{ route('pacientes.show', $paciente) }}" class="btn btn-xs btn-soft">Ver</a>
                            <a href="{{ route('pacientes.edit', $paciente) }}" class="btn btn-xs btn-warning">Editar</a>
                            @if (!$paciente->verificado)
                                <form action="{{ route('pacientes.verify', $paciente) }}" method="POST" onsubmit="return confirm('¿Verificar paciente?')">
                                    @csrf
                                    <button class="btn btn-xs btn-success">Verificar</button>
                                </form>
                            @else
                                <form action="{{ route('pacientes.verify', $paciente) }}" method="POST" onsubmit="return confirm('¿Quitar verificación al paciente?')">
                                    @csrf
                                    <button class="btn btn-xs btn-outline btn-error">Quitar verificación</button>
                                </form>
                            @endif
                            <form action="{{ route('pacientes.destroy', $paciente) }}" method="POST" onsubmit="return confirm('¿Eliminar paciente?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-xs btn-error">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No hay pacientes registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection