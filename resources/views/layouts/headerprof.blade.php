<header class="border-bottom bg-white shadow-sm">
    <div class="container-fluid py-3 px-4 d-flex flex-wrap gap-3 align-items-center justify-content-between">
        <div>
            <div class="fw-bold fs-4 text-primary">CoDentaL</div>
            <div class="text-muted small">{{ $paciente->pnom ?? session('nom') }} @if(!empty($paciente)) - Expediente activo @endif</div>
        </div>

        <form class="flex-grow-1" style="max-width: 420px;" action="{{ route('pacientes.index') }}" method="GET">
            <div class="input-group">
                <span class="input-group-text bg-light">Buscar</span>
                <input type="search" name="q" class="form-control" placeholder="Paciente, teléfono o cita">
            </div>
        </form>

        <div class="d-flex align-items-center gap-2">
            <span class="badge text-bg-secondary">{{ session('rol') }}</span>
            <span class="fw-semibold">{{ session('nom') }}</span>
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm">Salir</button>
            </form>
        </div>
    </div>

    <div class="border-top bg-dark">
        <div class="container-fluid px-4">
            <ul class="nav nav-pills py-2 gap-2 flex-wrap">
                <li class="nav-item"><a class="nav-link {{ ($activeTab ?? '') === 'agenda' ? 'active' : 'text-white' }}" href="{{ route('agenda') }}">Agenda</a></li>
                <li class="nav-item"><a class="nav-link {{ ($activeTab ?? '') === 'pacientes' ? 'active' : 'text-white' }}" href="{{ route('pacientes.index') }}">Pacientes</a></li>
                <li class="nav-item"><a class="nav-link {{ ($activeTab ?? '') === 'caja' ? 'active' : 'text-white' }}" href="{{ route('caja.facturacion') }}">Caja</a></li>
                @if((session('rol') ?? '') === 'admin')
                    <li class="nav-item"><a class="nav-link {{ ($activeTab ?? '') === 'admin' ? 'active' : 'text-white' }}" href="{{ route('administracion') }}">Administración</a></li>
                @endif
            </ul>
        </div>
    </div>

    @if(!empty($paciente))
        <div class="border-top bg-light">
            <div class="container-fluid px-4">
                <ul class="nav nav-tabs pt-2">
                    <li class="nav-item"><a class="nav-link {{ ($activeTab ?? '') === 'personal' ? 'active' : '' }}" href="{{ route('pacientes.personal', ['id_pac' => $paciente->idp]) }}">Ficha</a></li>
                    <li class="nav-item"><a class="nav-link {{ ($activeTab ?? '') === 'datos' ? 'active' : '' }}" href="{{ route('pacientes.datos', ['id_pac' => $paciente->idp]) }}">Datos</a></li>
                    <li class="nav-item"><a class="nav-link {{ ($activeTab ?? '') === 'antecedentes' ? 'active' : '' }}" href="{{ route('pacientes.antecedentes', ['id_pac' => $paciente->idp]) }}">Antecedentes</a></li>
                    <li class="nav-item"><a class="nav-link {{ ($activeTab ?? '') === 'odontograma' ? 'active' : '' }}" href="{{ route('odontograma.index', ['id_pac' => $paciente->idp]) }}">Odontograma</a></li>
                    <li class="nav-item"><a class="nav-link {{ ($activeTab ?? '') === 'plan' ? 'active' : '' }}" href="{{ route('pacientes.plan_tratamiento', ['id_pac' => $paciente->idp]) }}">Tratamientos</a></li>
                    <li class="nav-item"><a class="nav-link {{ ($activeTab ?? '') === 'facturacion' ? 'active' : '' }}" href="{{ route('caja.facturacion', ['id_pac' => $paciente->idp]) }}">Facturación</a></li>
                </ul>
            </div>
        </div>
    @endif
</header>