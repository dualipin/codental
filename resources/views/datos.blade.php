<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos del paciente - CoDentaL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; }
        .card-shell { border: 0; border-radius: 18px; box-shadow: 0 10px 26px rgba(15, 23, 42, .08); }
    </style>
</head>
<body>
    @include('layouts.headerprof')

    <main class="container py-4">
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

        @include('pacientes.header_paciente')

        <div class="card card-shell p-4 mb-4">
            <h1 class="h4 mb-1">Datos personales</h1>
            <p class="mb-0 text-muted">Actualiza la información general del expediente del paciente activo.</p>
        </div>

        <form class="card card-shell p-4" method="POST" action="{{ route('pacientes.datos.update', ['idPac' => $paciente->idp]) }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label">Nombre(s)</label><input type="text" name="pnom" class="form-control" value="{{ old('pnom', $paciente->pnom) }}" required></div>
                <div class="col-md-4"><label class="form-label">Apellido paterno</label><input type="text" name="papp" class="form-control" value="{{ old('papp', $paciente->papp) }}" required></div>
                <div class="col-md-4"><label class="form-label">Apellido materno</label><input type="text" name="papm" class="form-control" value="{{ old('papm', $paciente->papm) }}" required></div>
                <div class="col-md-3"><label class="form-label">Fecha de nacimiento</label><input type="date" name="pnac" class="form-control" value="{{ old('pnac', $paciente->pnac) }}" required></div>
                <div class="col-md-3"><label class="form-label">Sexo</label><input type="text" name="psex" class="form-control" value="{{ old('psex', $paciente->psex) }}" required></div>
                <div class="col-md-3"><label class="form-label">Teléfono</label><input type="text" name="ptel" class="form-control" value="{{ old('ptel', $paciente->ptel) }}" required></div>
                <div class="col-md-3"><label class="form-label">Correo</label><input type="email" name="pcor" class="form-control" value="{{ old('pcor', $paciente->pcor) }}" required></div>
                <div class="col-md-4"><label class="form-label">Ocupación</label><input type="text" name="pocu" class="form-control" value="{{ old('pocu', $paciente->pocu) }}" required></div>
                <div class="col-md-4"><label class="form-label">Estado civil</label><input type="text" name="pciv" class="form-control" value="{{ old('pciv', $paciente->pciv) }}" required></div>
                <div class="col-md-4"><label class="form-label">Estado</label><input type="text" name="pest" class="form-control" value="{{ old('pest', $paciente->pest) }}" required></div>
                <div class="col-md-6"><label class="form-label">Municipio</label><input type="text" name="pmun" class="form-control" value="{{ old('pmun', $paciente->pmun) }}" required></div>
                <div class="col-md-6"><label class="form-label">Dirección</label><input type="text" name="pdir" class="form-control" value="{{ old('pdir', $paciente->pdir) }}" required></div>
                <div class="col-md-6"><label class="form-label">Religión</label><input type="text" name="prel" class="form-control" value="{{ old('prel', $paciente->prel) }}"></div>
                <div class="col-md-6"><label class="form-label">Enviado por</label><input type="text" name="penv" class="form-control" value="{{ old('penv', $paciente->penv) }}"></div>
                <div class="col-12"><label class="form-label">Motivo de consulta</label><input type="text" name="pmot" class="form-control" value="{{ old('pmot', $paciente->pmot) }}"></div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('pacientes.personal', ['id_pac' => $paciente->idp]) }}" class="btn btn-light">Volver a la ficha</a>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>
    </main>
</body>
</html>
