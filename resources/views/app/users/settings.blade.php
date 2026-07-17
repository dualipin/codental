@extends('layouts.app')
@section('app-content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Configuración de usuario</h1>
        <a href="{{ route('usuarios.profile', $usuario) }}" class="btn btn-soft">Volver</a>
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

    @if (session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('usuarios.settings.update', $usuario) }}" class="card p-6 max-w-xl">
        @csrf
        @method('PATCH')

        <div class="grid gap-4">
            <label class="form-control">
                <span class="label">Nueva contraseña</span>
                <input type="password" name="password" class="input w-full" required>
            </label>

            <label class="form-control">
                <span class="label">Confirmar contraseña</span>
                <input type="password" name="password_confirmation" class="input w-full" required>
            </label>
        </div>

        <div class="mt-6">
            <button class="btn btn-primary">Guardar contraseña</button>
        </div>
    </form>
@endsection
