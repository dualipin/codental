@extends('layouts/main')
@section('content')
    <div class="flex flex-1 items-center justify-center bg-secondary/20 p-4">
        <div class="card bg-base-100 w-full max-w-sm shadow-xl">
            <div class="card-body gap-6">
                <!-- Nombre de la empresa -->
                <h2 class="text-primary font-display font-bold text-center text-4xl">CoDental</h2>

                <!-- Alerta de Errores -->
                @if ($errors->any())
                    <div role="alert" class="alert alert-error text-sm py-2 px-3 flex gap-2">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <!-- Formulario -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Campo Usuario -->
                    <div class="form-control">
                        <label class="label justify-start" for="usuario">
                            <span class="label-text font-semibold">Usuario</span>
                        </label>
                        <input type="text" id="usuario" name="usuario" required
                               class="input input-bordered w-full focus:input-primary"/>
                    </div>

                    <!-- Campo Contraseña -->
                    <div class="form-control">
                        <label class="label justify-start" for="contrasena">
                            <span class="label-text font-semibold">Contraseña</span>
                        </label>
                        <input type="password" id="contrasena" name="contrasena" required
                               class="input input-bordered w-full focus:input-primary"/>
                    </div>

                    <!-- Botón de Envío -->
                    <div class="form-control mt-4">
                        <button type="submit"
                                class="btn btn-primary w-full text-base font-bold uppercase tracking-wider">
                            Iniciar sesión
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection