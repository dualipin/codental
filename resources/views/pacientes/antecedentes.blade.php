<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antecedentes - CoDentaL</title>
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
            <h1 class="h4 mb-1">Antecedentes clinicos</h1>
            <p class="mb-0 text-muted">Registro medico y odontologico resumido del paciente activo.</p>
        </div>

        <form class="card card-shell p-4" method="POST" action="{{ route('pacientes.antecedentes.update', ['idPac' => $paciente->idp]) }}">
            @csrf

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Antecedentes hereditarios familiares</label>
                    <textarea name="hfam" rows="2" class="form-control">{{ old('hfam', optional($antecedente)->hfam) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Alergias</label>
                    <input type="text" name="ale" class="form-control" value="{{ old('ale', optional($antecedente)->ale) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Medicacion actual</label>
                    <input type="text" name="meda" class="form-control" value="{{ old('meda', optional($antecedente)->meda) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nombre del medico tratante</label>
                    <input type="text" name="nmed" class="form-control" value="{{ old('nmed', optional($antecedente)->nmed) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Telefono del medico</label>
                    <input type="text" name="mtel" class="form-control" value="{{ old('mtel', optional($antecedente)->mtel) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Grupo sanguineo</label>
                    <input type="text" name="san" class="form-control" value="{{ old('san', optional($antecedente)->san) }}">
                </div>

                <div class="col-md-8">
                    <label class="form-label">Observaciones bucales</label>
                    <input type="text" name="obs_buc" class="form-control" value="{{ old('obs_buc', optional($antecedente)->obs_buc) }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Motivo de consulta (clinico)</label>
                    <textarea name="mot" rows="2" class="form-control">{{ old('mot', optional($antecedente)->mot) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Interrogatorio</label>
                    <textarea name="inte" rows="2" class="form-control">{{ old('inte', optional($antecedente)->inte) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Resultados de laboratorio</label>
                    <textarea name="lab" rows="2" class="form-control">{{ old('lab', optional($antecedente)->lab) }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('pacientes.personal', ['id_pac' => $paciente->idp]) }}" class="btn btn-light">Volver a ficha</a>
                <button type="submit" class="btn btn-primary">Guardar antecedentes</button>
            </div>
        </form>
    </main>
</body>
</html>
