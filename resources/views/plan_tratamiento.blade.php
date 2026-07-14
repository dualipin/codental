<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan de Tratamiento - CoDentaL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; }
        .card-shell { border: 0; border-radius: 18px; box-shadow: 0 10px 26px rgba(15, 23, 42, .08); }
    </style>
</head>
<body>
    @include('layouts.headerprof')

    <main class="container py-4">
        @include('pacientes.header_paciente')

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card card-shell p-3 h-100">
                    <div class="text-muted small">Total contratado</div>
                    <div class="h4 mb-0">$ {{ number_format((float) ($caja->mon_tot ?? 0), 2) }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-shell p-3 h-100">
                    <div class="text-muted small">Total abonado</div>
                    <div class="h4 mb-0 text-success">$ {{ number_format((float) ($caja->mon_abo ?? 0), 2) }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-shell p-3 h-100">
                    <div class="text-muted small">Saldo pendiente</div>
                    <div class="h4 mb-0 text-danger">$ {{ number_format((float) ($caja->sal_pen ?? 0), 2) }}</div>
                </div>
            </div>
        </div>

        <div class="card card-shell p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h4 mb-0">Plan de tratamiento</h1>
                <span class="badge bg-secondary">{{ $caja->est_cue ?? 'Pendiente' }}</span>
            </div>

            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tratamiento</th>
                            <th>Estado</th>
                            <th>Monto</th>
                            <th>Descuento</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tratamientos as $tratamiento)
                            <tr>
                                <td>{{ $tratamiento->fec_sel ? \Carbon\Carbon::parse($tratamiento->fec_sel)->format('d/m/Y') : '' }}</td>
                                <td>{{ $tratamiento->tratamiento?->nom ?? 'Tratamiento' }}</td>
                                <td><span class="badge bg-info text-dark">{{ $tratamiento->est }}</span></td>
                                <td>{{ number_format((float) $tratamiento->mon, 2) }}</td>
                                <td>{{ number_format((float) $tratamiento->mon_des, 2) }}</td>
                                <td>{{ number_format((float) $tratamiento->mon_fin, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-muted">No hay tratamientos seleccionados para este paciente.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
