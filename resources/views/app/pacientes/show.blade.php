@extends('layouts.app')

@section('app-content')
    @php
        use App\Enums\UserRolEnum;

        $hc = $paciente->historiaClinica;
        $usuario = auth()->user();
        $rolUsuario = $usuario?->rol instanceof UserRolEnum ? $usuario->rol->value : (string) $usuario?->rol;
        $puedeEditarAntecedentesMedicos = in_array($rolUsuario, [UserRolEnum::DENTISTA->value, UserRolEnum::ADMINISTRADOR->value], true);
    @endphp

    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">{{ $paciente->nombre }} {{ $paciente->apellido_paterno }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('agenda', ['paciente' => $paciente->id]) }}" class="btn btn-primary">Agendar cita</a>
            @if ($puedeEditarAntecedentesMedicos)
                <a href="{{ route('pacientes.historia-clinica.edit', $paciente) }}" class="btn btn-primary">Editar historia clínica</a>
            @endif
            @if (!$paciente->verificado)
                <form action="{{ route('pacientes.verify', $paciente) }}" method="POST" onsubmit="return confirm('¿Verificar paciente?')">
                    @csrf
                    <button class="btn btn-success">Verificar</button>
                </form>
            @else
                <form action="{{ route('pacientes.verify', $paciente) }}" method="POST" onsubmit="return confirm('¿Quitar verificación al paciente?')">
                    @csrf
                    <button class="btn btn-outline btn-error">Quitar verificación</button>
                </form>
            @endif
            <a href="{{ route('pacientes.edit', $paciente) }}" class="btn btn-warning">Editar</a>
            <a href="{{ route('pacientes.index') }}" class="btn btn-soft">Volver</a>
        </div>
    </div>

    <div class="card p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <span class="label">Nombre completo</span>
                <p class="font-semibold">{{ $paciente->nombre }} {{ $paciente->apellido_paterno }} {{ $paciente->apellido_materno }}</p>
            </div>
            <div>
                <span class="label">Teléfono</span>
                <p>{{ $paciente->telefono }}</p>
            </div>
            <div>
                <span class="label">Correo</span>
                <p>{{ $paciente->correo_electronico ?? '—' }}</p>
            </div>
            <div>
                <span class="label">Sexo</span>
                <p>@switch($paciente->sexo->value) @case('M') Masculino @break @case('F') Femenino @break @case('O') Otro @break @endswitch</p>
            </div>
            <div>
                <span class="label">Fecha de nacimiento</span>
                <p>{{ $paciente->fecha_nacimiento->format('d/m/Y') }}</p>
            </div>
            <div>
                <span class="label">Edad</span>
                <p>{{ $paciente->fecha_nacimiento->age }} años</p>
            </div>
            <div>
                <span class="label">Estado civil</span>
                <p>{{ $paciente->estado_civil ?? '—' }}</p>
            </div>
            <div>
                <span class="label">Ocupación</span>
                <p>{{ $paciente->ocupacion ?? '—' }}</p>
            </div>
            <div>
                <span class="label">Religión</span>
                <p>{{ $paciente->religion ?? '—' }}</p>
            </div>
            <div class="md:col-span-2">
                <span class="label">Dirección</span>
                <p>{{ $paciente->direccion ?? '—' }}, {{ $paciente->municipio ?? '' }}, {{ $paciente->estado ?? '' }}</p>
            </div>
            <div>
                <span class="label">Verificado</span>
                <p>@if ($paciente->verificado) <span class="badge badge-success">Sí</span> @else <span class="badge badge-soft">No</span> @endif</p>
            </div>
        </div>
    </div>

    <div role="tablist" class="tabs tabs-bordered mb-6">
        <input type="radio" name="expediente_tabs" role="tab" class="tab" aria-label="Historia Clínica" checked="checked" />
        <div role="tabpanel" class="tab-content py-4">
            @if ($hc)
                <div class="space-y-4">
                    <div class="collapse collapse-arrow bg-base-200">
                        <input type="checkbox" checked />
                        <div class="collapse-title font-semibold">Antecedentes heredofamiliares</div>
                        <div class="collapse-content text-sm">{{ $hc->antecedentes_hereditarios ?? 'Sin registro' }}</div>
                    </div>
                    <div class="collapse collapse-arrow bg-base-200">
                        <input type="checkbox" />
                        <div class="collapse-title font-semibold">Antecedentes patológicos</div>
                        <div class="collapse-content text-sm">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <div><span class="font-medium">Alergias:</span> {{ $hc->alergias ?? '—' }}</div>
                                <div><span class="font-medium">Medicación actual:</span> {{ $hc->medicacion_actual ?? '—' }}</div>
                                <div><span class="font-medium">Médico:</span> {{ $hc->nombre_medico ?? '—' }}</div>
                                <div><span class="font-medium">Tel. médico:</span> {{ $hc->telefono_medico ?? '—' }}</div>
                                <div class="md:col-span-2"><span class="font-medium">Enfermedades previas:</span> {{ is_array($hc->enfermedades_previas) ? implode(', ', $hc->enfermedades_previas) : ($hc->enfermedades_previas ?? '—') }}</div>
                                <div class="md:col-span-2"><span class="font-medium">Otras enfermedades:</span> {{ $hc->otras_enfermedades ?? '—' }}</div>
                                <div class="md:col-span-2"><span class="font-medium">Cirugías / hospitalizaciones:</span> {{ $hc->cirugias_hospitalizaciones ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="collapse collapse-arrow bg-base-200">
                        <input type="checkbox" />
                        <div class="collapse-title font-semibold">Antecedentes no patológicos</div>
                        <div class="collapse-content text-sm">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <div><span class="font-medium">Grupo sanguíneo:</span> {{ $hc->grupo_sanguineo ?? '—' }}</div>
                                <div><span class="font-medium">Habitos tóxicos:</span> {{ is_array($hc->habitos_toxicos) ? implode(', ', $hc->habitos_toxicos) : ($hc->habitos_toxicos ?? '—') }}</div>
                                <div><span class="font-medium">Estilo de vida:</span> {{ is_array($hc->estilo_vida) ? implode(', ', $hc->estilo_vida) : ($hc->estilo_vida ?? '—') }}</div>
                                <div><span class="font-medium">Ginecoobstétricos:</span> {{ is_array($hc->ginecoobstetricos) ? implode(', ', $hc->ginecoobstetricos) : ($hc->ginecoobstetricos ?? '—') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="collapse collapse-arrow bg-base-200">
                        <input type="checkbox" />
                        <div class="collapse-title font-semibold">Exploración y diagnóstico</div>
                        <div class="collapse-content text-sm">
                            <div class="grid grid-cols-1 gap-2">
                                <div><span class="font-medium">Padecimiento actual:</span> {{ $hc->padecimiento_actual ?? '—' }}</div>
                                <div><span class="font-medium">Interrogatorio de sistemas:</span> {{ $hc->interrogatorio_sistemas ?? '—' }}</div>
                                <div><span class="font-medium">Exámenes de laboratorio:</span> {{ $hc->examenes_laboratorio ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="collapse collapse-arrow bg-base-200">
                        <input type="checkbox" />
                        <div class="collapse-title font-semibold">Antecedentes bucodentales y ATM</div>
                        <div class="collapse-content text-sm">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <div class="md:col-span-2"><span class="font-medium">Bucodentales:</span> {{ is_array($hc->antecedentes_bucodentales) ? implode(', ', $hc->antecedentes_bucodentales) : ($hc->antecedentes_bucodentales ?? '—') }}</div>
                                <div class="md:col-span-2"><span class="font-medium">ATM:</span> {{ is_array($hc->atm) ? implode(', ', $hc->atm) : ($hc->atm ?? '—') }}</div>
                                <div class="md:col-span-2"><span class="font-medium">Tejidos blandos/duros:</span> {{ $hc->tejidos_blandos_duros ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-soft">Sin historia clínica registrada.</div>
            @endif
        </div>

        <input type="radio" name="expediente_tabs" role="tab" class="tab" aria-label="Consultas" />
        <div role="tabpanel" class="tab-content py-4">
            @if ($paciente->consultas->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Motivo</th>
                                <th>Odontólogo</th>
                                <th>Nota evolución</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paciente->consultas as $consulta)
                                <tr>
                                    <td>{{ $consulta->fecha_consulta->format('d/m/Y') }}</td>
                                    <td class="max-w-xs truncate">{{ $consulta->motivo_consulta ?? '—' }}</td>
                                    <td>{{ $consulta->odontologo?->nombre ?? '—' }}</td>
                                    <td class="max-w-xs truncate">{{ $consulta->nota_evolucion ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-soft">Sin consultas registradas.</div>
            @endif
        </div>

        <input type="radio" name="expediente_tabs" role="tab" class="tab" aria-label="Citas" />
        <div role="tabpanel" class="tab-content py-4">
            @if ($paciente->citas->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Dentista</th>
                                <th>Estatus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paciente->citas as $cita)
                                <tr>
                                    <td>{{ $cita->fecha_inicio?->format('d/m/Y H:i') }}</td>
                                    <td>{{ $cita->dentista?->nombre ?? '—' }}</td>
                                    <td><span class="badge badge-soft">{{ $cita->estatus?->value ?? '—' }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-soft">Sin citas registradas.</div>
            @endif
        </div>

        <input type="radio" name="expediente_tabs" role="tab" class="tab" aria-label="Presupuestos" />
        <div role="tabpanel" class="tab-content py-4">
            @if ($paciente->presupuestos->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th>Emisión</th>
                                <th>Vencimiento</th>
                                <th>Monto</th>
                                <th>Estado</th>
                                <th>Dentista</th>
                                <th>Abonos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paciente->presupuestos as $presupuesto)
                                <tr>
                                    <td>{{ $presupuesto->fecha_emision->format('d/m/Y') }}</td>
                                    <td>{{ $presupuesto->fecha_vencimiento->format('d/m/Y') }}</td>
                                    <td>${{ number_format($presupuesto->monto, 2) }}</td>
                                    <td>
                                        @switch($presupuesto->estado)
                                            @case('aprobado') <span class="badge badge-success">Aprobado</span> @break
                                            @case('rechazado') <span class="badge badge-error">Rechazado</span> @break
                                            @default <span class="badge badge-soft">Pendiente</span>
                                        @endswitch
                                    </td>
                                    <td>{{ $presupuesto->dentista?->nombre ?? '—' }}</td>
                                    <td>${{ number_format($presupuesto->distribuciones->sum('monto_aplicado'), 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-soft">Sin presupuestos registrados.</div>
            @endif
        </div>
    </div>
@endsection
