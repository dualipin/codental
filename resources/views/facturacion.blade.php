<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturación - CoDentaL</title>
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
                    <h1 class="h4 mb-1">Facturación y pagos</h1>
                    <p class="mb-0 text-muted">Solo recep registra pagos. Admin y dent ven la información en modo lectura.</p>
                </div>
                <div class="text-muted">Rol: <strong>{{ $rol }}</strong></div>
            </div>
        </div>

        @if(empty($paciente))
            <div class="row g-3">
                @forelse($pacientes as $pacienteBase)
                    <div class="col-lg-6">
                        <div class="card panel-card p-3 h-100">
                            <h2 class="h5 mb-1">{{ $pacienteBase->pnom }} {{ $pacienteBase->papp }} {{ $pacienteBase->papm }}</h2>
                            <div class="text-muted small">Teléfono: {{ $pacienteBase->ptel }}</div>
                            <div class="mt-3">
                                <a href="{{ route('caja.facturacion') }}?id_pac={{ $pacienteBase->idp }}" class="btn btn-primary btn-sm">Abrir facturación</a>
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
                        <div>Total contratado</div>
                        <h3>{{ number_format((float) $caja->mon_tot, 2) }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric" style="background: linear-gradient(135deg, #16a34a, #14532d);">
                        <div>Total abonado</div>
                        <h3>{{ number_format((float) $caja->mon_abo, 2) }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric" style="background: linear-gradient(135deg, #f59e0b, #92400e);">
                        <div>Saldo pendiente</div>
                        <h3>{{ number_format((float) $caja->sal_pen, 2) }}</h3>
                    </div>
                </div>
            </div>

            <div class="card panel-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h2 class="h5 mb-1">Paciente: {{ $paciente->pnom }} {{ $paciente->papp }} {{ $paciente->papm }}</h2>
                        <div class="text-muted small">Estado de cuenta: {{ $caja->est_cue }}</div>
                    </div>
                    <a href="{{ route('caja.facturacion') }}" class="btn btn-outline-secondary btn-sm">Volver al listado</a>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="card panel-card p-4 h-100">
                        <h2 class="h5 mb-3">Tratamientos</h2>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
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
                                            <td>{{ $tratamiento->tratamiento?->nom ?? 'Tratamiento' }}</td>
                                            <td><span class="badge bg-secondary">{{ $tratamiento->est }}</span></td>
                                            <td>{{ number_format((float) $tratamiento->mon, 2) }}</td>
                                            <td>{{ number_format((float) $tratamiento->mon_des, 2) }}</td>
                                            <td>{{ number_format((float) $tratamiento->mon_fin, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-muted">No hay tratamientos seleccionados.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card panel-card p-4 mb-4">
                        <h2 class="h5 mb-3">Resumen de cuenta</h2>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between"><span>Descuentos totales</span><strong>{{ number_format((float) $caja->des_tot, 2) }}</strong></li>
                            <li class="list-group-item d-flex justify-content-between"><span>Monto neto</span><strong>{{ number_format((float) $caja->mon_net, 2) }}</strong></li>
                            <li class="list-group-item d-flex justify-content-between"><span>Saldo pendiente</span><strong>{{ number_format((float) $caja->sal_pen, 2) }}</strong></li>
                            <li class="list-group-item d-flex justify-content-between"><span>Estado</span><strong>{{ $caja->est_cue }}</strong></li>
                        </ul>
                    </div>

                    <div class="card panel-card p-4 mb-4">
                        <h2 class="h5 mb-3">Registrar abono</h2>
                        @if($puedeRegistrar)
                            <form method="POST" action="{{ route('caja.abonos.store') }}" class="vstack gap-3">
                                @csrf
                                <input type="hidden" name="id_pac" value="{{ $paciente->idp }}">
                                <div>
                                    <label class="form-label">Tratamiento</label>
                                    <select name="id_pts" class="form-select">
                                        <option value="">Sin tratamiento específico</option>
                                        @foreach($tratamientos as $tratamiento)
                                            <option value="{{ $tratamiento->id_pts }}">{{ $tratamiento->tratamiento?->nom ?? 'Tratamiento' }} - {{ number_format((float) $tratamiento->mon_fin, 2) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label">Monto</label>
                                        <input type="number" step="0.01" min="0.01" name="mon" class="form-control" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Fecha</label>
                                        <input type="date" name="fec_abo" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label">Método de pago</label>
                                    <select name="met_pag" class="form-select" required>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Transferencia">Transferencia</option>
                                        <option value="Tarjeta">Tarjeta</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                </div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label">Referencia</label>
                                        <input type="text" name="ref" class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Descuento aplicado</label>
                                        <input type="number" step="0.01" min="0" name="des_apl" class="form-control" value="0">
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label">Observaciones</label>
                                    <textarea name="obs" rows="2" class="form-control"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">Registrar abono</button>
                            </form>
                        @else
                            <div class="alert alert-info mb-0">Tu rol solo permite ver la facturación. La recepción registra abonos.</div>
                        @endif
                    </div>

                    <div class="card panel-card p-4">
                        <h2 class="h5 mb-3">Historial de abonos</h2>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Monto</th>
                                        <th>Método</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($abonos as $abono)
                                        <tr>
                                            <td>{{ $abono->fec_abo ? $abono->fec_abo->format('d/m/Y') : '' }}</td>
                                            <td>{{ number_format((float) $abono->mon, 2) }}</td>
                                            <td>{{ $abono->met_pag }}</td>
                                            <td><span class="badge {{ $abono->est === 'Activo' ? 'bg-success' : 'bg-danger' }}">{{ $abono->est }}</span></td>
                                            <td>
                                                @if($puedeRegistrar && $abono->est === 'Activo')
                                                    <form method="POST" action="{{ route('caja.abonos.anular', $abono) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Anular</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-muted">Sin abonos registrados.</td></tr>
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
