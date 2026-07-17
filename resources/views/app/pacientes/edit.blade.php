@extends('layouts.app')

@section('app-content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Editar paciente</h1>
        <a href="{{ route('pacientes.index') }}" class="btn btn-soft">Volver</a>
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

    <form method="POST" action="{{ route('pacientes.update', $paciente) }}" class="card p-6 max-w-7xl">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <label class="form-control">
                <span class="label">Nombre</span>
                <input type="text" name="nombre" class="input w-full" value="{{ old('nombre', $paciente->nombre) }}" required>
            </label>

            <label class="form-control">
                <span class="label">Apellido paterno</span>
                <input type="text" name="apellido_paterno" class="input w-full" value="{{ old('apellido_paterno', $paciente->apellido_paterno) }}" required>
            </label>

            <label class="form-control">
                <span class="label">Apellido materno</span>
                <input type="text" name="apellido_materno" class="input w-full" value="{{ old('apellido_materno', $paciente->apellido_materno) }}">
            </label>

            <label class="form-control">
                <span class="label">Teléfono</span>
                <input type="text" name="telefono" class="input w-full" value="{{ old('telefono', $paciente->telefono) }}" required maxlength="10">
            </label>

            <label class="form-control">
                <span class="label">Correo electrónico</span>
                <input type="email" name="correo_electronico" class="input w-full" value="{{ old('correo_electronico', $paciente->correo_electronico) }}">
            </label>

            <label class="form-control">
                <span class="label">Sexo</span>
                <select name="sexo" class="select w-full" required>
                    <option value="M" @selected(old('sexo', $paciente->sexo->value) == 'M')>Masculino</option>
                    <option value="F" @selected(old('sexo', $paciente->sexo->value) == 'F')>Femenino</option>
                    <option value="O" @selected(old('sexo', $paciente->sexo->value) == 'O')>Otro</option>
                </select>
            </label>

            <label class="form-control">
                <span class="label">Fecha de nacimiento</span>
                <input type="date" name="fecha_nacimiento" class="input w-full" value="{{ old('fecha_nacimiento', $paciente->fecha_nacimiento->format('Y-m-d')) }}" required>
            </label>

            <label class="form-control">
                <span class="label">Estado civil</span>
                <select name="estado_civil" class="select w-full">
                    <option value="">Seleccionar</option>
                    <option value="Soltero" @selected(old('estado_civil', $paciente->estado_civil) == 'Soltero')>Soltero</option>
                    <option value="Casado" @selected(old('estado_civil', $paciente->estado_civil) == 'Casado')>Casado</option>
                    <option value="Divorciado" @selected(old('estado_civil', $paciente->estado_civil) == 'Divorciado')>Divorciado</option>
                    <option value="Viudo" @selected(old('estado_civil', $paciente->estado_civil) == 'Viudo')>Viudo</option>
                    <option value="Unión libre" @selected(old('estado_civil', $paciente->estado_civil) == 'Unión libre')>Unión libre</option>
                </select>
            </label>

            <label class="form-control">
                <span class="label">Ocupación</span>
                <input type="text" name="ocupacion" class="input w-full" value="{{ old('ocupacion', $paciente->ocupacion) }}">
            </label>

            <label class="form-control">
                <span class="label">Religión</span>
                <input type="text" name="religion" class="input w-full" value="{{ old('religion', $paciente->religion) }}">
            </label>

            <label class="form-control md:col-span-2">
                <span class="label">Dirección</span>
                <input type="text" name="direccion" class="input w-full" value="{{ old('direccion', $paciente->direccion) }}">
            </label>

            <label class="form-control">
                <span class="label">Estado</span>
                <input type="text" name="estado" class="input w-full" value="{{ old('estado', $paciente->estado) }}">
            </label>

            <label class="form-control">
                <span class="label">Municipio</span>
                <input type="text" name="municipio" class="input w-full" value="{{ old('municipio', $paciente->municipio) }}">
            </label>
        </div>

        <div class="mt-6">
            <button class="btn btn-primary">Actualizar</button>
        </div>
    </form>
@endsection