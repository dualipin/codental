<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturación y Pagos - CoDentaL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; font-family: 'Segoe UI', sans-serif; }
        .panel-card { border: 0; border-radius: 20px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08); }
        .metric { border-radius: 18px; padding: 18px; color: #fff; min-height: 120px; }
        .metric h3 { margin: 0; font-size: 34px; font-weight: 800; }
    </style>
</head>
<body>
    @include('layouts.headerprof')

    <main class="container py-4">
        @include('pacientes.header_paciente')

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card panel-card p-4 mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h1 class="h4 mb-1">Facturación y pagos (Ledger Inmutable)</h1>
                    <p class="mb-0 text-muted">Registro de pagos seguro y sin modificaciones. Anulaciones vía compensación.</p>
                </div>
                <div class="text-muted">Rol: <strong>{{ $rol }}</strong></div>
            </div>
        </div>

        @if(empty($paciente))
            <div class="row g-3">
                @forelse($pacientes as $pacienteBase)
                    <div class="col-lg-6">
                        <div class="card panel-card p-3 h-100">
                            <h2 class="h5 mb-1">{{ $pacienteBase->nombre }} {{ $pacienteBase->apellido_paterno }}</h2>
                            <div class="text-muted small">Teléfono: {{ $pacienteBase->telefono }}</div>
                            <div class="mt-3">
                                <a href="{{ route('caja.facturacion') }}?id_pac={{ $pacienteBase->id }}" class="btn btn-primary btn-sm">Abrir facturación</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning mb-0">No hay pacientes para mostrar.</div>
                    </div>
                @endforelse
            </div>
        @else
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="metric" style="background: linear-gradient(135deg, #0284c7, #0f172a);">
                        <div>Cargos Aprobados</div>
                        <h3>${{ number_format((float) $total_cargos, 2) }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric" style="background: linear-gradient(135deg, #16a34a, #14532d);">
                        <div>Abonos (Neto)</div>
                        <h3>${{ number_format((float) $total_abonos, 2) }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric" style="background: linear-gradient(135deg, #f59e0b, #92400e);">
                        <div>Saldo Pendiente</div>
                        <h3>${{ number_format((float) $saldo, 2) }}</h3>
                    </div>
                </div>
            </div>

            <div class="card panel-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h2 class="h5 mb-1">Paciente: {{ $paciente->nombre }} {{ $paciente->apellido_paterno }}</h2>
                        <div class="text-muted small">Estado de cuenta exacto calculado desde el Ledger.</div>
                    </div>
                    <a href="{{ route('caja.facturacion') }}" class="btn btn-outline-secondary btn-sm">Volver al listado</a>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="card panel-card p-4 h-100">
                        <h2 class="h5 mb-3">Detalles de Presupuestos Aprobados</h2>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>Tratamiento</th>
                                        <th>Estado</th>
                                        <th>Precio</th>
                                        <th>Descuento</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tratamientos as $detalle)
                                        <tr>
                                            <td>{{ $detalle->tratamientoCatalogo?->nombre ?? 'Tratamiento ID ' . $detalle->tratamiento_catalogo_id }}</td>
                                            <td><span class="badge bg-secondary">{{ $detalle->estado_tratamiento }}</span></td>
                                            <td>${{ number_format((float) $detalle->precio_congelado, 2) }}</td>
                                            <td>${{ number_format((float) $detalle->monto_descuento, 2) }}</td>
                                            <td>${{ number_format((float) ($detalle->precio_congelado - $detalle->monto_descuento), 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-muted">No hay tratamientos aprobados.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card panel-card p-4 mb-4">
                        <h2 class="h5 mb-3">Registrar Pago (API)</h2>
                        <div class="alert alert-info">
                            La funcionalidad de registro ahora se gestiona a través de nuestra nueva API en <code>CajaController</code> consumida por el frontend Vue/Inertia (Próximamente). 
                        </div>
                    </div>

                    <div class="card panel-card p-4">
                        <h2 class="h5 mb-3">Historial Inmutable (Ledger)</h2>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Monto</th>
                                        <th>Método</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($abonos as $mov)
                                        <tr class="{{ $mov->tipo_movimiento === 'anulacion' ? 'table-danger' : 'table-success' }}">
                                            <td>{{ \Carbon\Carbon::parse($mov->fecha_hora)->format('d/m/Y H:i') }}</td>
                                            <td>{{ strtoupper($mov->tipo_movimiento) }}</td>
                                            <td>${{ number_format((float) $mov->monto, 2) }}</td>
                                            <td>{{ $mov->metodo_pago }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-muted">Sin movimientos registrados.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </main>
</body>
</html>
