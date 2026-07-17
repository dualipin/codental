@extends('layouts.pdf')
@section('document-title', 'Confirmación de Cita')
@section('document-styles')

    <style>

        .title {
            font-size: 22px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 5px;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 13px;
            font-weight: 700;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .info-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .info-item {
            flex: 1 1 45%;
            min-width: 200px;
            background: #f9fafb;
            padding: 12px 15px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }

        .info-item.full {
            flex: 1 1 100%;
        }

        .info-label {
            font-size: 10px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .info-value {
            font-size: 12px;
            font-weight: 500;
            color: #1f2937;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-pendiente {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-confirmada {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-cancelada {
            background: #fee2e2;
            color: #991b1b;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
        }

        .footer-note {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            padding: 15px;
            margin-top: 20px;
            text-align: left;
            font-size: 10px;
        }

        .footer-note-title {
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 8px;
        }

        .footer-note ul {
            margin-left: 15px;
        }

        .footer-note li {
            margin-bottom: 4px;
            color: #1e3a8a;
        }

        .table thead tr td {
            font-size: 14px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 8px;
            text-align: center;
        }
    </style>
@endsection

@section('document-content')

    <div class="header">
        <div class="title">Comprobante de Cita Agendada</div>
    </div>

    <div class="section">
        <div class="section-title">Datos de la Cita</div>

        <table class="table">
            <thead>
            <tr>
                <th>Folio</th>
                <th>Estatus</th>
                <th>Fecha de Inicio</th>
                <th>Fecha de Fin</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    #{{ str_pad($cita->id, 6, '0', STR_PAD_LEFT) }}
                </td>
                <td>
                    <span class="badge badge-{{ $cita->estatus->value === 'PENDIENTE' ? 'pendiente' : ($cita->estatus->value === 'CONFIRMADA' ? 'confirmada' : 'cancelada') }}">
                        {{ $cita->estatus->value }}
                    </span>
                </td>
                <td>
                    {{ $cita->fecha_inicio->format('d/m/Y H:i') }}
                </td>
                <td>
                    {{ $cita->fecha_fin->format('d/m/Y H:i') }}
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Datos del Paciente</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Nombre Completo</div>
                <div class="info-value">
                    {{ $cita->paciente->nombre }} {{ $cita->paciente->apellido_paterno }} {{ $cita->paciente->apellido_materno ?? '' }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Teléfono</div>
                <div class="info-value">{{ $cita->paciente->telefono }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $cita->paciente->email ?? 'No registrado' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">ID Paciente</div>
                <div class="info-value">#{{ str_pad($cita->paciente->id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Datos del Dentista</div>

        <table class="table">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Especialidad</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    Dr(a). {{ $cita->dentista->nombre }} {{ $cita->dentista->apellido_paterno }} {{ $cita->dentista->apellido_materno ?? '' }}
                </td>
                <td>
                    {{ $cita->dentista->especialidad ?? 'Odontología General' }}
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="footer-note">
        <div class="footer-note-title">Instrucciones Importantes</div>
        <ul>
            <li>Presente este comprobante (impreso o en su móvil) el día de su cita.</li>
            <li>Llegue 10 minutos antes de su horario programado.</li>
            <li>Si necesita cancelar o reprogramar, contacte a la clínica con al menos 2 horas de anticipación.</li>
            <li>Traiga su identificación oficial.</li>
        </ul>
    </div>

    <div class="footer">
        <p>Documento generado automáticamente el {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Este comprobante es válido como confirmación de cita.</p>
    </div>

@endsection