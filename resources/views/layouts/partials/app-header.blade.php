<header class="navbar bg-base-100 border-b border-base-300 sticky top-0 z-30">
    @php
        $hour = now()->hour;
        $greeting = $hour < 12 ? 'Hola, buenos días' : ($hour < 19 ? 'Hola, buenas tardes' : 'Hola, buenas noches');
    @endphp

    <div class="flex-none lg:hidden">
        <label for="app-drawer" aria-label="Abrir menú" class="btn btn-square btn-ghost">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </label>
    </div>

    <div class="flex-1">
        <span id="app-greeting" class="text-xl sm:text-2xl font-bold font-display">{{ $greeting }}</span>
    </div>

    <div class="flex-none flex items-center gap-1">
        {{--        <button class="btn btn-circle btn-ghost btn-sm" aria-label="Notificaciones">--}}
        {{--            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"--}}
        {{--                 stroke="currentColor">--}}
        {{--                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"--}}
        {{--                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>--}}
        {{--            </svg>--}}
        {{--        </button>--}}

        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar placeholder">
                <div class="bg-primary text-primary-content rounded-full size-8 flex items-center justify-center">
                    @if(auth()->user()->foto_usuario)
                        <img src="{{ Storage::url(auth()->user()->foto_usuario) }}" alt="Foto de usuario"
                             class="rounded-full size-8">
                    @else
                        <span class="text-sm font-semibold">{{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}</span>
                    @endif
                </div>
            </div>
            <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-lg">
                <li><a href="{{ route('usuarios.profile', ['user' => auth()->user()->id]) }}"><i
                                class="bi bi-person"></i> Perfil</a></li>
                <li><a href="{{route('usuarios.settings', ['user' => auth()->user()->id])}}"><i class="bi bi-gear"></i> Configuración</a></li>
                <li><a class="text-error" href="{{ route('logout') }}"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a></li>
            </ul>
        </div>
    </div>
</header>

<script>
  (() => {
    const greetingElement = document.getElementById('app-greeting')

    if (!greetingElement) {
      return
    }

    const hour = new Date().getHours()
    greetingElement.textContent = hour < 12
      ? 'Hola, buenos días'
      : hour < 19
        ? 'Hola, buenas tardes'
        : 'Hola, buenas noches'
  })()
</script>

