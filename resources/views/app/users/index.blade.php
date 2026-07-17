@extends('layouts.app')

@section('app-content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Usuarios</h1>
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">Nuevo usuario</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success mb-4">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="table table-zebra">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($usuarios as $usuario)
                    <tr>
                        <td>
                            @if ($usuario->foto_usuario)
                                <div class="avatar">
                                    <div class="w-8 rounded-full">
                                        <img src="{{ Storage::url($usuario->foto_usuario) }}" alt="Foto">
                                    </div>
                                </div>
                            @else
                                <div class="avatar placeholder">
                                    <div class="bg-neutral text-neutral-content w-8 rounded-full">
                                        <span class="text-xs">{{ substr($usuario->nombre, 0, 1) }}{{ substr($usuario->apellido_paterno, 0, 1) }}</span>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td>{{ $usuario->nombre }} {{ $usuario->apellido_paterno }} {{ $usuario->apellido_materno }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td><span class="badge badge-soft badge-primary">{{ $usuario->rol->value }}</span></td>
                        <td>{{ $usuario->telefono }}</td>
                        <td class="flex gap-1">
                            <a href="{{ route('usuarios.profile', $usuario) }}" class="btn btn-xs btn-soft">Ver</a>
                            <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-xs btn-warning">Editar</a>
                            <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" onsubmit="return confirm('¿Eliminar usuario?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-xs btn-error">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No hay usuarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
