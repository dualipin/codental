@extends('layouts.app')

@section('app-content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Nuevo paciente</h1>
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

    <form method="POST" action="{{ route('pacientes.store') }}" class="card p-6 max-w-7xl">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <label class="form-control">
                <span class="label">Nombre</span>
                <input type="text" name="nombre" class="input w-full" value="{{ old('nombre') }}" required>
            </label>

            <label class="form-control">
                <span class="label">Apellido paterno</span>
                <input type="text" name="apellido_paterno" class="input w-full" value="{{ old('apellido_paterno') }}" required>
            </label>

            <label class="form-control">
                <span class="label">Apellido materno</span>
                <input type="text" name="apellido_materno" class="input w-full" value="{{ old('apellido_materno') }}">
            </label>

            <label class="form-control">
                <span class="label">Teléfono</span>
                <input type="text" name="telefono" class="input w-full" value="{{ old('telefono') }}" required maxlength="10">
            </label>

            <label class="form-control">
                <span class="label">Correo electrónico</span>
                <input type="email" name="correo_electronico" class="input w-full" value="{{ old('correo_electronico') }}">
            </label>

            <label class="form-control">
                <span class="label">Sexo</span>
                <select name="sexo" class="select w-full" required>
                    <option value="">Seleccionar</option>
                    <option value="M" @selected(old('sexo') == 'M')>Masculino</option>
                    <option value="F" @selected(old('sexo') == 'F')>Femenino</option>
                    <option value="O" @selected(old('sexo') == 'O')>Otro</option>
                </select>
            </label>

            <label class="form-control">
                <span class="label">Fecha de nacimiento</span>
                <input type="date" name="fecha_nacimiento" class="input w-full" value="{{ old('fecha_nacimiento') }}" required>
            </label>

            <label class="form-control">
                <span class="label">Estado civil</span>
                <select name="estado_civil" class="select w-full">
                    <option value="">Seleccionar</option>
                    <option value="Soltero" @selected(old('estado_civil') == 'Soltero')>Soltero</option>
                    <option value="Casado" @selected(old('estado_civil') == 'Casado')>Casado</option>
                    <option value="Divorciado" @selected(old('estado_civil') == 'Divorciado')>Divorciado</option>
                    <option value="Viudo" @selected(old('estado_civil') == 'Viudo')>Viudo</option>
                    <option value="Unión libre" @selected(old('estado_civil') == 'Unión libre')>Unión libre</option>
                </select>
            </label>

            <label class="form-control">
                <span class="label">Ocupación</span>
                <input type="text" name="ocupacion" class="input w-full" value="{{ old('ocupacion') }}">
            </label>

            <label class="form-control">
                <span class="label">Religión</span>
                <input type="text" name="religion" class="input w-full" value="{{ old('religion') }}">
            </label>

            <label class="form-control md:col-span-2">
                <span class="label">Dirección</span>
                <input type="text" name="direccion" class="input w-full" value="{{ old('direccion') }}">
            </label>

            <label class="form-control">
                <span class="label">Estado</span>
                <input type="text" name="estado" class="input w-full" value="{{ old('estado') }}">
            </label>

            <label class="form-control">
                <span class="label">Municipio</span>
                <input type="text" name="municipio" class="input w-full" value="{{ old('municipio') }}">
            </label>
        </div>

        <div class="mt-6">
            <button class="btn btn-primary">Guardar</button>
        </div>
    </form>
@endsection