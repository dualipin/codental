@extends('layouts.agendar-cita')
@section('agenda-content')
    <section class="max-w-4xl mx-auto px-4 py-8">
        <div class="card bg-base-100 shadow-xl border border-base-300">
            <div class="card-body">
                <!-- Encabezado -->
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-primary/10 p-3 rounded-full">
                        <i class="bi bi-person-check text-primary text-2xl p-1"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-base-content">Verificar Mis Datos</h2>
                </div>

                <form action="{{ route('agendar-cita.identificar-paciente') }}" method="POST" class="space-y-6">
                    @csrf
                    <!-- Teléfono -->
                    <div class="form-control w-full">
                        <label class="label" for="telefono">
                            <span class="label-text font-medium">Teléfono Registrado</span>
                            <span class="label-text-alt text-base-content/70">(10 dígitos)</span>
                        </label>
                        <input type="tel"
                               name="telefono"
                               id="telefono"
                               class="input input-bordered w-full focus:input-primary"
                               placeholder="Ej. 9363845947"
                               maxlength="10"
                               pattern="[0-9]{10}"
                               required>
                    </div>

                    @error('paciente_no_encontrado')
                    <div class="alert alert-error" role="alert">
                        <div class="flex-1 gap-2 flex">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <label>{{ $errors->first('paciente_no_encontrado') }}</label>
                        </div>
                    </div>
                    @enderror

                    <!-- Botones de acción -->
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-3 pt-4 border-t border-base-200">
                        <a href="{{ route('agendar-cita') }}"
                           class="btn btn-ghost btn-outline w-full sm:w-auto">
                            <i class="bi bi-arrow-left"></i>
                            Atrás
                        </a>

                        <button type="submit"
                                class="btn btn-primary w-full sm:w-auto">
                            <i class="bi bi-search"></i>
                            Buscar y Continuar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection