@extends('layouts.app')

@section('app-content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Editar usuario</h1>
        <a href="{{ route('usuarios') }}" class="btn btn-soft">Volver</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-error mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('usuarios.update', $usuario) }}" class="card p-6 max-w-7xl" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <label class="form-control">
                <span class="label">Nombre</span>
                <input type="text" name="nombre" class="input w-full" value="{{ old('nombre', $usuario->nombre) }}" required>
            </label>

            <label class="form-control">
                <span class="label">Apellido paterno</span>
                <input type="text" name="apellido_paterno" class="input w-full" value="{{ old('apellido_paterno', $usuario->apellido_paterno) }}" required>
            </label>

            <label class="form-control">
                <span class="label">Apellido materno</span>
                <input type="text" name="apellido_materno" class="input w-full" value="{{ old('apellido_materno', $usuario->apellido_materno) }}" required>
            </label>

            <label class="form-control">
                <span class="label">Email</span>
                <input type="email" name="email" class="input w-full" value="{{ old('email', $usuario->email) }}" required>
            </label>

            <label class="form-control">
                <span class="label">Contraseña <span class="text-xs opacity-60">(dejar vacío para mantener)</span></span>
                <input type="password" name="password" class="input w-full">
            </label>

            <label class="form-control">
                <span class="label">Teléfono</span>
                <input type="text" name="telefono" class="input w-full" value="{{ old('telefono', $usuario->telefono) }}" required maxlength="10">
            </label>

            <label class="form-control">
                <span class="label">Sexo</span>
                <select name="sexo" class="select w-full" required>
                    <option value="M" @selected(old('sexo', $usuario->sexo->value) == 'M')>Masculino</option>
                    <option value="F" @selected(old('sexo', $usuario->sexo->value) == 'F')>Femenino</option>
                    <option value="O" @selected(old('sexo', $usuario->sexo->value) == 'O')>Otro</option>
                </select>
            </label>

            <label class="form-control">
                <span class="label">Fecha de nacimiento</span>
                <input type="date" name="fecha_nacimiento" class="input w-full" value="{{ old('fecha_nacimiento', $usuario->fecha_nacimiento?->format('Y-m-d')) }}" required>
            </label>

            <label class="form-control">
                <span class="label">Rol</span>
                <select name="rol" class="select w-full" required>
                    <option value="dent" @selected(old('rol', $usuario->rol->value) == 'dent')>Dentista</option>
                    <option value="recep" @selected(old('rol', $usuario->rol->value) == 'recep')>Recepcionista</option>
                    <option value="admin" @selected(old('rol', $usuario->rol->value) == 'admin')>Administrador</option>
                </select>
            </label>

            <label class="form-control">
                <span class="label">Especialidad</span>
                <input type="text" name="especialidad" class="input w-full" value="{{ old('especialidad', $usuario->especialidad) }}">
            </label>

            <label class="form-control">
                <span class="label">Estado civil</span>
                <input type="text" name="estado_civil" class="input w-full" value="{{ old('estado_civil', $usuario->estado_civil) }}">
            </label>

            <label class="form-control md:col-span-2">
                <span class="label">Dirección</span>
                <input type="text" name="direccion" class="input w-full" value="{{ old('direccion', $usuario->direccion) }}">
            </label>

            <label class="form-control">
                <span class="label">Estado</span>
                <input type="text" name="estado" class="input w-full" value="{{ old('estado', $usuario->estado) }}">
            </label>

            <label class="form-control">
                <span class="label">Municipio</span>
                <input type="text" name="municipio" class="input w-full" value="{{ old('municipio', $usuario->municipio) }}">
            </label>

            <label class="form-control">
                <span class="label">Cédula profesional</span>
                <input type="text" name="cedula" class="input w-full" value="{{ old('cedula', $usuario->cedula) }}">
            </label>

            <label class="form-control md:col-span-2">
                <span class="label">Foto de perfil</span>
                <input type="file" name="foto_usuario" class="file-input w-full" accept="image/*">
                @if ($usuario->foto_usuario)
                    <span class="label-text-alt mt-1">Foto actual: <a href="{{ Storage::url($usuario->foto_usuario) }}" target="_blank" class="link link-primary">ver imagen</a></span>
                @endif
            </label>
        </div>

        <div class="mt-6">
            <button class="btn btn-primary">Actualizar</button>
        </div>
    </form>
@endsection
