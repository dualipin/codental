<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha clínica - CoDentaL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(180deg, #f8fafc 0%, #edf5ff 100%); }
        .card-shell { border: 0; border-radius: 18px; box-shadow: 0 10px 26px rgba(15, 23, 42, .08); }
        .soft-pill { background: rgba(59, 130, 246, .08); color: #1d4ed8; }
    </style>
</head>
<body>
    @include('layouts.headerprof')

    <main class="container py-4">
        @include('pacientes.header_paciente')

        <div class="card card-shell p-4 mb-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <span class="badge soft-pill mb-2">Ficha activa</span>
                    <h1 class="h3 mb-1">{{ $paciente->pnom }} {{ $paciente->papp }} {{ $paciente->papm }}</h1>
                    <p class="mb-0 text-muted">Desde aquí navegas al expediente, odontograma, tratamientos y facturación del mismo paciente.</p>
                </div>
                <div class="text-end">
                    <div class="fw-semibold">{{ $paciente->ptel }}</div>
                    <div class="text-muted small">{{ $paciente->pcor }}</div>
                    <div class="text-muted small">Cama / caja: {{ $caja->est_cue ?? 'Pendiente' }}</div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card card-shell p-4 h-100">
                    <div class="text-muted small">Edad</div>
                    <div class="h4 mb-0">{{ $paciente->pnac ? \Carbon\Carbon::parse($paciente->pnac)->age : 'N/D' }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-shell p-4 h-100">
                    <div class="text-muted small">Última cita</div>
                    <div class="h6 mb-0">{{ $ultimaCita ? \Carbon\Carbon::parse($ultimaCita->fec_i)->format('d/m/Y H:i') : 'Sin citas registradas' }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-shell p-4 h-100">
                    <div class="text-muted small">Saldo pendiente</div>
                    <div class="h4 mb-0 text-danger">$ {{ number_format((float) ($caja->sal_pen ?? 0), 2) }}</div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <a class="card card-shell p-4 text-decoration-none text-dark h-100" href="{{ route('pacientes.datos', ['id_pac' => $paciente->idp]) }}">
                    <h2 class="h5">Datos personales</h2>
                    <p class="mb-0 text-muted">Editar información general del expediente.</p>
                </a>
            </div>
            <div class="col-md-6">
                <a class="card card-shell p-4 text-decoration-none text-dark h-100" href="{{ route('pacientes.antecedentes', ['id_pac' => $paciente->idp]) }}">
                    <h2 class="h5">Antecedentes</h2>
                    <p class="mb-0 text-muted">Revisar el historial médico y odontológico.</p>
                </a>
            </div>
            <div class="col-md-6">
                <a class="card card-shell p-4 text-decoration-none text-dark h-100" href="{{ route('odontograma.index', ['id_pac' => $paciente->idp]) }}">
                    <h2 class="h5">Odontograma</h2>
                    <p class="mb-0 text-muted">Abrir las piezas, caras y estados clínicos.</p>
                </a>
            </div>
            <div class="col-md-6">
                <a class="card card-shell p-4 text-decoration-none text-dark h-100" href="{{ route('pacientes.plan_tratamiento', ['id_pac' => $paciente->idp]) }}">
                    <h2 class="h5">Plan de tratamiento</h2>
                    <p class="mb-0 text-muted">Ver tratamientos, descuentos y avances.</p>
                </a>
            </div>
            <div class="col-md-6">
                <a class="card card-shell p-4 text-decoration-none text-dark h-100" href="{{ route('caja.facturacion', ['id_pac' => $paciente->idp]) }}">
                    <h2 class="h5">Facturación y pagos</h2>
                    <p class="mb-0 text-muted">Abrir el resumen financiero y los abonos.</p>
                </a>
            </div>
            <div class="col-md-6">
                <a class="card card-shell p-4 text-decoration-none text-dark h-100" href="{{ route('pacientes.index') }}">
                    <h2 class="h5">Cambiar paciente</h2>
                    <p class="mb-0 text-muted">Volver al listado para seleccionar otro expediente.</p>
                </a>
            </div>
        </div>
    </main>
</body>
</html>
