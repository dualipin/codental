<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pacientes - CoDentaL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; }
        .card-shell { border: 0; border-radius: 18px; box-shadow: 0 10px 26px rgba(15, 23, 42, .08); }
    </style>
</head>
<body>
    @include('layouts.headerprof')

    <main class="container py-4">
        <div class="card card-shell p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h1 class="h4 mb-1">Pacientes</h1>
                    <p class="mb-0 text-muted">Listado según el rol actual.</p>
                </div>
                <a href="{{ route('pacientes.regispacientes') }}" class="btn btn-primary">Registrar nuevo paciente</a>
            </div>
        </div>

        <div class="row g-3">
            @forelse($pacientes as $paciente)
                <div class="col-lg-6">
                    <div class="card card-shell p-3 h-100">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <h2 class="h5 mb-1">{{ $paciente->pnom }} {{ $paciente->papp }} {{ $paciente->papm }}</h2>
                                <div class="text-muted small">Edad aproximada: {{ $paciente->pnac ? \Carbon\Carbon::parse($paciente->pnac)->age : 'N/D' }}</div>
                                <div class="text-muted small">Teléfono: {{ $paciente->ptel }}</div>
                                <div class="text-muted small">Próxima cita: {{ $paciente->fecha_cita ? \Carbon\Carbon::parse($paciente->fecha_cita)->format('d/m/Y H:i') : 'Sin cita agendada' }}</div>
                            </div>
                            <div class="btn-group">
                                <a href="{{ route('pacientes.seleccionar', ['idPac' => $paciente->idp]) }}" class="btn btn-outline-secondary btn-sm">Abrir ficha</a>
                                <a href="{{ route('pacientes.personal', ['id_pac' => $paciente->idp]) }}" class="btn btn-outline-secondary btn-sm">Personal</a>
                                <a href="{{ route('odontograma.index') }}?id_pac={{ $paciente->idp }}" class="btn btn-outline-primary btn-sm">Odontograma</a>
                                <a href="{{ route('agenda') }}?paciente={{ $paciente->idp }}" class="btn btn-outline-success btn-sm">Agenda</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning mb-0">No hay pacientes para mostrar.</div>
                </div>
            @endforelse
        </div>
    </main>
</body>
</html>
