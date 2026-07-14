<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Odontograma - CoDentaL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; font-family: 'Segoe UI', sans-serif; }
        .panel-card { border: 0; border-radius: 20px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08); }
        .legend-chip { display: inline-flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 999px; background: #fff; border: 1px solid #e2e8f0; margin: 0 8px 8px 0; font-size: 13px; }
        .legend-dot { width: 12px; height: 12px; border-radius: 999px; display: inline-block; }
        .reg-table td, .reg-table th { vertical-align: middle; }
        .panel-disabled { opacity: .6; pointer-events: none; }
        .tiny { font-size: 12px; }
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

        @if($sinPaciente)
            <div class="card panel-card p-4">
                <h1 class="h4 mb-2">Odontograma</h1>
                <p class="mb-0 text-muted">Selecciona primero un paciente desde el listado de pacientes para abrir su odontograma.</p>
            </div>
        @else
            <div class="card panel-card p-4 mb-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <h1 class="h4 mb-1">Odontograma de {{ $paciente->pnom }} {{ $paciente->papp }} {{ $paciente->papm }}</h1>
                        <div class="text-muted">Teléfono: {{ $paciente->ptel }} | Correo: {{ $paciente->pcor }}</div>
                    </div>
                    <div class="text-muted">{{ $odontoInicial ? 'Odontograma inicial registrado' : 'Odontograma inicial pendiente' }}</div>
                </div>
            </div>

            <div class="mb-4">
                @foreach($enfermedades as $enfermedad)
                    <span class="legend-chip">
                        <span class="legend-dot js-color-dot" data-color="{{ $enfermedad->color }}"></span>
                        {{ $enfermedad->nom }}
                    </span>
                @endforeach
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card panel-card p-4 h-100 {{ $odontoInicial ? 'panel-disabled' : '' }}">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h2 class="h5 mb-1">Odontograma Inicial</h2>
                                <div class="text-muted tiny">Solo editable si no existe un odontograma inicial.</div>
                            </div>
                            <span class="badge bg-primary">Inicial</span>
                        </div>

                        <form method="POST" action="{{ route('odontograma.store') }}" id="form-inicial">
                            @csrf
                            <input type="hidden" name="id_pac" value="{{ $paciente->idp }}">
                            <input type="hidden" name="fase" value="inicial">
                            <input type="hidden" name="registros_json" id="json-inicial">

                            <div class="row g-2 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label tiny">Diente</label>
                                    <select class="form-select form-select-sm" id="diente-inicial">
                                        <option value="">Selecciona</option>
                                        @foreach($dientes as $diente)
                                            <option value="{{ $diente->num_fdi }}">{{ $diente->num_fdi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label tiny">Cara</label>
                                    <select class="form-select form-select-sm" id="cara-inicial">
                                        <option value="">Selecciona</option>
                                        @foreach($caras as $cara)
                                            <option value="{{ $cara->abr }}">{{ $cara->abr }} - {{ $cara->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label tiny">Enfermedad</label>
                                    <select class="form-select form-select-sm" id="enf-inicial">
                                        <option value="">Selecciona</option>
                                        @foreach($enfermedades as $enfermedad)
                                            <option value="{{ $enfermedad->id_enf }}" data-nom="{{ $enfermedad->nom }}" data-color="{{ $enfermedad->color }}">{{ $enfermedad->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label tiny">Observaciones</label>
                                    <input type="text" class="form-control form-control-sm" id="obs-inicial" placeholder="Notas clínicas">
                                </div>
                            </div>

                            <div class="d-flex gap-2 mb-3">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="add-inicial">Agregar fila</button>
                                <button type="submit" class="btn btn-primary btn-sm">Guardar inicial</button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm reg-table align-middle" id="table-inicial">
                                    <thead>
                                        <tr>
                                            <th>Diente</th>
                                            <th>Cara</th>
                                            <th>Enfermedad</th>
                                            <th>Obs</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </form>

                        @if($odontoInicial)
                            <div class="alert alert-info mt-3 mb-0">Ya existe un odontograma inicial. Se mostrará el de tratamiento a la derecha para edición.</div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card panel-card p-4 h-100 {{ $odontoInicial ? '' : 'panel-disabled' }}">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h2 class="h5 mb-1">Odontograma Final</h2>
                                <div class="text-muted tiny">Editable solo cuando ya existe el odontograma inicial.</div>
                            </div>
                            <span class="badge bg-success">Final</span>
                        </div>

                        <form method="POST" action="{{ route('odontograma.store') }}" id="form-tratamiento">
                            @csrf
                            <input type="hidden" name="id_pac" value="{{ $paciente->idp }}">
                            <input type="hidden" name="fase" value="tratamiento">
                            <input type="hidden" name="registros_json" id="json-tratamiento">

                            <div class="row g-2 mb-3">
                                <div class="col-md-3">
                                    <label class="form-label tiny">Diente</label>
                                    <select class="form-select form-select-sm" id="diente-tratamiento">
                                        <option value="">Selecciona</option>
                                        @foreach($dientes as $diente)
                                            <option value="{{ $diente->num_fdi }}">{{ $diente->num_fdi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label tiny">Cara</label>
                                    <select class="form-select form-select-sm" id="cara-tratamiento">
                                        <option value="">Selecciona</option>
                                        @foreach($caras as $cara)
                                            <option value="{{ $cara->abr }}">{{ $cara->abr }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label tiny">Enfermedad</label>
                                    <select class="form-select form-select-sm" id="enf-tratamiento">
                                        <option value="">Selecciona</option>
                                        @foreach($enfermedades as $enfermedad)
                                            <option value="{{ $enfermedad->id_enf }}" data-nom="{{ $enfermedad->nom }}" data-color="{{ $enfermedad->color }}">{{ $enfermedad->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label tiny">Estado</label>
                                    <select class="form-select form-select-sm" id="est-tratamiento">
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="En tratamiento">En tratamiento</option>
                                        <option value="Completado">Completado</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label tiny">Observaciones</label>
                                    <input type="text" class="form-control form-control-sm" id="obs-tratamiento" placeholder="Notas clínicas">
                                </div>
                            </div>

                            <div class="d-flex gap-2 mb-3">
                                <button type="button" class="btn btn-outline-success btn-sm" id="add-tratamiento">Agregar fila</button>
                                <button type="submit" class="btn btn-success btn-sm">Guardar final</button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm reg-table align-middle" id="table-tratamiento">
                                    <thead>
                                        <tr>
                                            <th>Diente</th>
                                            <th>Cara</th>
                                            <th>Enfermedad</th>
                                            <th>Estado</th>
                                            <th>Obs</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </form>

                        @if(! $odontoInicial)
                            <div class="alert alert-warning mt-3 mb-0">Este panel se habilita después de guardar el odontograma inicial.</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-3">
                <div class="col-lg-6">
                    <div class="card panel-card p-4 h-100">
                        <h3 class="h6 mb-3">Registros guardados - Inicial</h3>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Diente</th>
                                        <th>Cara</th>
                                        <th>Enfermedad</th>
                                        <th>Obs</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($registrosIniciales as $registro)
                                        <tr>
                                            <td>{{ $registro['diente'] }}</td>
                                            <td>{{ $registro['cara'] }}</td>
                                            <td><span class="badge js-color-badge" data-color="{{ $registro['color'] }}">{{ $registro['enfermedad'] }}</span></td>
                                            <td>{{ $registro['obs'] }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-muted">Sin registros.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card panel-card p-4 h-100">
                        <h3 class="h6 mb-3">Registros guardados - Final</h3>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Diente</th>
                                        <th>Cara</th>
                                        <th>Enfermedad</th>
                                        <th>Obs</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($registrosTratamiento as $registro)
                                        <tr>
                                            <td>{{ $registro['diente'] }}</td>
                                            <td>{{ $registro['cara'] }}</td>
                                            <td><span class="badge js-color-badge" data-color="{{ $registro['color'] }}">{{ $registro['enfermedad'] }}</span></td>
                                            <td>{{ $registro['obs'] }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-muted">Sin registros.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </main>

    <script>
        function initEditor(prefix, initialRows) {
            const state = Array.isArray(initialRows) ? initialRows.slice() : [];
            const tableBody = document.querySelector(`#table-${prefix} tbody`);
            const hidden = document.getElementById(`json-${prefix}`);
            const diente = document.getElementById(`diente-${prefix}`);
            const cara = document.getElementById(`cara-${prefix}`);
            const enfermedad = document.getElementById(`enf-${prefix}`);
            const obs = document.getElementById(`obs-${prefix}`);
            const addButton = document.getElementById(`add-${prefix}`);
            const estado = document.getElementById(`est-${prefix}`);

            function sync() {
                hidden.value = JSON.stringify(state);
                tableBody.innerHTML = '';

                if (!state.length) {
                    const colspan = prefix === 'tratamiento' ? 6 : 5;
                    tableBody.innerHTML = `<tr><td colspan="${colspan}" class="text-muted">Sin registros añadidos.</td></tr>`;
                    return;
                }

                state.forEach(function (row, index) {
                    const tr = document.createElement('tr');
                    tr.innerHTML = prefix === 'tratamiento'
                        ? `<td>${row.diente}</td><td>${row.cara}</td><td><span class="badge" style="background:${row.color}">${row.enfermedad}</span></td><td>${row.est || ''}</td><td>${row.obs || ''}</td><td><button type="button" class="btn btn-sm btn-outline-danger">Quitar</button></td>`
                        : `<td>${row.diente}</td><td>${row.cara}</td><td><span class="badge" style="background:${row.color}">${row.enfermedad}</span></td><td>${row.obs || ''}</td><td><button type="button" class="btn btn-sm btn-outline-danger">Quitar</button></td>`;
                    tr.querySelector('button').addEventListener('click', function () {
                        state.splice(index, 1);
                        sync();
                    });
                    tableBody.appendChild(tr);
                });
            }

            addButton.addEventListener('click', function () {
                if (!diente.value || !cara.value || !enfermedad.value) {
                    alert('Selecciona diente, cara y enfermedad.');
                    return;
                }

                const option = enfermedad.options[enfermedad.selectedIndex];
                state.push({
                    diente: diente.value,
                    cara: cara.value,
                    id_enf: option.value,
                    enfermedad: option.dataset.nom || option.textContent,
                    color: option.dataset.color || '#64748b',
                    obs: obs.value || '',
                    est: estado ? estado.value : 'Pendiente'
                });

                diente.value = '';
                cara.value = '';
                enfermedad.value = '';
                obs.value = '';
                if (estado) {
                    estado.value = 'Pendiente';
                }

                sync();
            });

            sync();
        }

        document.addEventListener('DOMContentLoaded', function () {
            const initialData = document.getElementById('odontograma-inicial-data');
            const treatmentData = document.getElementById('odontograma-tratamiento-data');

            initEditor('inicial', JSON.parse(initialData.textContent || '[]'));
            initEditor('tratamiento', JSON.parse(treatmentData.textContent || '[]'));

            document.querySelectorAll('.js-color-dot, .js-color-badge').forEach(function (element) {
                const color = element.dataset.color;
                if (color) {
                    element.style.backgroundColor = color;
                    element.style.color = '#fff';
                }
            });
        });
    </script>

    <script id="odontograma-inicial-data" type="application/json"><?php echo json_encode($registrosIniciales ?? []); ?></script>
    <script id="odontograma-tratamiento-data" type="application/json"><?php echo json_encode($registrosTratamiento ?? []); ?></script>
</body>
</html>
