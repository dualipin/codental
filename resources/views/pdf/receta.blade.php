<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Receta Médica</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .info { margin-bottom: 20px; }
        .info table { width: 100%; }
        .info th { text-align: left; width: 120px; }
        .medicines { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .medicines th, .medicines td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .medicines th { background-color: #f2f2f2; }
        .footer { position: fixed; bottom: 30px; left: 0; right: 0; text-align: center; font-size: 12px; }
        .signature { margin-top: 50px; text-align: center; width: 200px; float: right; border-top: 1px solid #000; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Clínica Dental</h1>
        <p>Receta Médica</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <th>Paciente:</th>
                <td>{{ $receta->cita->paciente->nombre }} {{ $receta->cita->paciente->apellido }}</td>
            </tr>
            <tr>
                <th>Fecha:</th>
                <td>{{ $receta->created_at->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Odontólogo:</th>
                <td>Dr/Dra. {{ $receta->cita->dentista->name }}</td>
            </tr>
        </table>
    </div>

    <h3>Indicaciones</h3>
    <table class="medicines">
        <thead>
            <tr>
                <th>Medicamento</th>
                <th>Dosis</th>
                <th>Frecuencia</th>
                <th>Duración</th>
            </tr>
        </thead>
        <tbody>
            @foreach($receta->detalles as $detalle)
            <tr>
                <td>{{ $detalle->medicamento }}</td>
                <td>{{ $detalle->dosis }}</td>
                <td>{{ $detalle->frecuencia }}</td>
                <td>{{ $detalle->duracion }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature">
        <p>Firma y Sello</p>
    </div>

    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }} - Codental
    </div>

</body>
</html>
