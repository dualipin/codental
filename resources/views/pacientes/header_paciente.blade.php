@php
	$pacienteActivo = $paciente ?? null;
	$idPacActivo = $pacienteActivo?->idp;
	$activeTab = $activeTab ?? '';
@endphp

<div class="card card-shell p-3 mb-4">
	<div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
		<div>
			<div class="text-muted small">Paciente activo</div>
			@if($pacienteActivo)
				<div class="fw-semibold">{{ $pacienteActivo->pnom }} {{ $pacienteActivo->papp }} {{ $pacienteActivo->papm }}</div>
				<div class="text-muted small">{{ $pacienteActivo->ptel }}{{ $pacienteActivo->pcor ? ' | '.$pacienteActivo->pcor : '' }}</div>
			@else
				<div class="fw-semibold">Sin paciente seleccionado</div>
				<div class="text-muted small">Selecciona uno para navegar la ficha clinica.</div>
			@endif
		</div>

		<form method="GET" action="{{ route('pacientes.personal') }}" class="d-flex gap-2 align-items-center">
			<label for="selector_paciente" class="small text-muted mb-0">Cambiar paciente</label>
			<select id="selector_paciente" name="id_pac" class="form-select form-select-sm" style="min-width: 240px;">
				<option value="">Selecciona</option>
				@foreach(($pacientesDisponibles ?? collect()) as $pacienteItem)
					<option value="{{ $pacienteItem->idp }}" {{ ($idPacActivo === $pacienteItem->idp || request('id_pac') === $pacienteItem->idp) ? 'selected' : '' }}>
						{{ $pacienteItem->pnom }} {{ $pacienteItem->papp }} {{ $pacienteItem->papm }}
					</option>
				@endforeach
			</select>
			<button type="submit" class="btn btn-sm btn-outline-primary">Abrir</button>
		</form>
	</div>

	@if($idPacActivo)
		<div class="mt-3 border-top pt-3">
			<div class="d-flex flex-wrap gap-2">
				<a href="{{ route('pacientes.personal', ['id_pac' => $idPacActivo]) }}" class="btn btn-sm {{ $activeTab === 'personal' ? 'btn-primary' : 'btn-outline-primary' }}">Ficha</a>
				<a href="{{ route('pacientes.datos', ['id_pac' => $idPacActivo]) }}" class="btn btn-sm {{ $activeTab === 'datos' ? 'btn-primary' : 'btn-outline-primary' }}">Datos</a>
				<a href="{{ route('pacientes.antecedentes', ['id_pac' => $idPacActivo]) }}" class="btn btn-sm {{ $activeTab === 'antecedentes' ? 'btn-primary' : 'btn-outline-primary' }}">Antecedentes</a>
				<a href="{{ route('odontograma.index', ['id_pac' => $idPacActivo]) }}" class="btn btn-sm {{ $activeTab === 'odontograma' ? 'btn-primary' : 'btn-outline-primary' }}">Odontograma</a>
				<a href="{{ route('pacientes.plan_tratamiento', ['id_pac' => $idPacActivo]) }}" class="btn btn-sm {{ $activeTab === 'plan' ? 'btn-primary' : 'btn-outline-primary' }}">Plan</a>
				<a href="{{ route('pacientes.facturacion', ['id_pac' => $idPacActivo]) }}" class="btn btn-sm {{ $activeTab === 'facturacion' ? 'btn-primary' : 'btn-outline-primary' }}">Facturacion</a>
			</div>
		</div>
	@endif
</div>
