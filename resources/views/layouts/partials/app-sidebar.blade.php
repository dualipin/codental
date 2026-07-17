<div class="drawer-side z-40">
    <label for="app-drawer" aria-label="Cerrar menú" class="drawer-overlay"></label>

    <aside class="bg-base-200 min-h-dvh w-72 flex flex-col border-r border-base-300">
        <div class="flex items-center gap-3 px-6 py-5 border-b border-base-300">
            {{--            <div class="w-8 h-8 rounded-full bg-primary"></div>--}}
            <a class="text-xl font-bold font-display">CoDental</a>
        </div>

        <nav class="flex-1 overflow-y-auto p-4">
            <ul class="menu menu-lg rounded-box gap-1">
                <li class="menu-title"><span>General</span></li>
                <li><a href="{{route('agenda')}}"><i class="bi bi-calendar-week-fill"></i> Agenda</a></li>
                <li><a href="#"><i class="bi bi-people-fill"></i> Pacientes</a></li>

                <li class="menu-title"><span>Gestión</span></li>
                <li><a href="#"><i class="bi bi-file-text-fill"></i> Plan de Tratamiento</a></li>
                <li><a href="#"><i class="bi bi-receipt"></i> Facturación</a></li>
                <li><a href="#"><i class="bi bi-file-earmark-bar-graph-fill"></i> Reportes</a></li>

                <li class="menu-title"><span>Administración</span></li>
                <li>
                    <details>
                        <summary>
                            <i class="bi bi-person-gear"></i> Usuarios
                        </summary>
                        <ul>
                            <li><a href="{{route('usuarios.create')}}"><i class="bi bi-person-plus-fill"></i> Crear Usuario</a></li>
                            <li><a href="{{route('usuarios')}}"><i class="bi bi-people-fill"></i> Listar Usuarios</a></li>
                        </ul>
                    </details>
                </li>
                <li><a href="#"><i class="bi bi-gear-fill"></i> Configuración</a></li>
            </ul>
        </nav>

        <div class="p-4 border-t border-base-300">
            <div class="flex items-center gap-3">
                <div class="avatar placeholder">
                    <div class="bg-primary text-primary-content rounded-full w-10">
                        <span class="text-sm font-semibold">AD</span>
                    </div>
                </div>
                <div class="text-sm">
                    <p class="font-semibold">Admin User</p>
                    <p class="text-base-content/60 text-xs">admin@codental.com</p>
                </div>
            </div>
        </div>
    </aside>
</div>
