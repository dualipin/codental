<div class="drawer-side z-40">
    <label for="app-drawer" aria-label="Cerrar menú" class="drawer-overlay"></label>

    <aside class="bg-base-200 min-h-dvh w-72 flex flex-col border-r border-base-300">
        <div class="flex items-center gap-3 px-6 py-5 border-b border-base-300">
            <a class="text-xl font-bold font-display flex items-center">
                <img src="{{ asset('icon.png') }}" alt="Logo" class="size-20 object-cover mr-2 rounded-full">
                CoDental
            </a>
        </div>

        <nav class="flex-1 overflow-y-auto p-4">
            @php
                $rol = auth()->user()?->rol?->value;
                $esAdmin = $rol === \App\Enums\UserRolEnum::ADMINISTRADOR->value;
                $esRecep = $rol === \App\Enums\UserRolEnum::RECEPCIONISTA->value;
                $esDent = $rol === \App\Enums\UserRolEnum::DENTISTA->value;
            @endphp

            <ul class="menu menu-lg rounded-box gap-1">
                <li class="menu-title"><span>General</span></li>
                @if($esRecep)
                    <li>
                        <a href="{{ route('recepcion.dashboard') }}">
                            <i class="bi bi-house-fill"></i> Dashboard
                        </a>
                    </li>
                @endif
                <li><a href="{{ route('agenda') }}"><i class="bi bi-calendar-week-fill"></i> Agenda</a></li>
                <li>
                    <details>
                        <summary>
                            <i class="bi bi-people-fill"></i> Pacientes
                        </summary>
                        <ul>
                            <li>
                                <a href="{{ route('pacientes.index') }}">
                                    <i class="bi bi-list-ul"></i>
                                    Listar Pacientes
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pacientes.create') }}">
                                    <i class="bi bi-person-plus-fill"></i>
                                    Registrar Paciente
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>

                <li class="menu-title"><span>Caja y Facturación</span></li>
                <li>
                    <details>
                        <summary>
                            <i class="bi bi-cash-coin"></i> Caja
                        </summary>
                        <ul>
                            <li>
                                <a href="{{ route('caja.facturacion') }}">
                                    <i class="bi bi-receipt"></i>
                                    Facturación / Abonos
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>

                @if($esAdmin)
                    <li class="menu-title"><span>Administración</span></li>
                    <li>
                        <details>
                            <summary>
                                <i class="bi bi-person-gear"></i> Usuarios
                            </summary>
                            <ul>
                                <li>
                                    <a href="{{ route('usuarios.create') }}">
                                        <i class="bi bi-person-plus-fill"></i>
                                        Crear Usuario
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('usuarios') }}">
                                        <i class="bi bi-people-fill"></i>
                                        Listar Usuarios
                                    </a>
                                </li>
                            </ul>
                        </details>
                    </li>
                    <li>
                        <details>
                            <summary>
                                <i class="bi bi-gear-fill"></i> Configuración
                            </summary>
                            <ul>
                                <li><a href="#"><i class="bi bi-currency-dollar"></i> Precios de Tratamientos</a></li>
                                <li><a href="#"><i class="bi bi-virus"></i> Catálogo de Enfermedades</a></li>
                            </ul>
                        </details>
                    </li>
                @endif
            </ul>
        </nav>

        <div class="p-4 border-t border-base-300">
            <div class="flex items-center gap-3">
                <div class="avatar placeholder">
                    <div class="bg-primary text-primary-content rounded-full size-12 flex items-center justify-center">
                        @if(auth()->user()->foto_usuario)
                            <img src="{{ Storage::url(auth()->user()->foto_usuario) }}" alt="Foto de usuario"
                                 class="rounded-full size-12">
                        @else
                            <span class="text-sm font-semibold">{{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}</span>
                        @endif
                    </div>
                </div>
                <div class="text-sm">
                    <p class="font-semibold">
                        {{ auth()->user()->nombre }} {{ auth()->user()->apellido_paterno }}
                    </p>
                    <p class="text-base-content/60 text-xs">
                        {{ auth()->user()->email }}
                    </p>
                </div>
            </div>
        </div>
    </aside>
</div>
