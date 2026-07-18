@extends('layouts.pdf')
@section('document-title', 'Evolución Clínica')
@section('document-styles')

    <style>

        .title {
            font-size: 22px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 25px;
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

        .soap-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 12px;
        }

        .soap-label {
            font-size: 11px;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 6px;
        }

        .soap-content {
            font-size: 12px;
            color: #374151;
            line-height: 1.5;
            white-space: pre-wrap;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th {
            background: #f3f4f6;
            font-size: 10px;
            font-weight: 700;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 10px 12px;
            text-align: left;
            border: 1px solid #e5e7eb;
        }

        .table td {
            font-size: 11px;
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            color: #1f2937;
        }

        .badge-completado {
            display: inline-block;
            background: #d1fae5;
            color: #065f46;
            font-size: 9px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 9999px;
            text-transform: uppercase;
        }

        .signature-area {
            margin-top: 50px;
            display: flex;
            justify-content: flex-end;
        }

        .signature-box {
            width: 250px;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #374151;
            padding-top: 8px;
            margin-top: 40px;
        }

        .signature-label {
            font-size: 10px;
            color: #6b7280;
        }

        .footer-note {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            padding: 15px;
            margin-top: 20px;
            font-size: 10px;
        }

        .footer-note-title {
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 6px;
        }

    </style>

@endsection

@section('document-content')

    <div class="header">
        <div class="title">Nota de Evolución Clínica</div>
        <div class="subtitle">Formato SOAP — Odontología</div>
    </div>

    <div class="section">
        <div class="section-title">Datos Generales</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Paciente</div>
                <div class="info-value">
                    {{ $evolucion->cita->paciente->nombre }}
                    {{ $evolucion->cita->paciente->apellido_paterno }}
                    {{ $evolucion->cita->paciente->apellido_materno ?? '' }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Fecha de Consulta</div>
                <div class="info-value">{{ $evolucion->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Odontólogo</div>
                <div class="info-value">
                    Dr(a). {{ $evolucion->odontologo->nombre ?? $evolucion->cita->dentista->nombre }}
                    {{ $evolucion->odontologo->apellido_paterno ?? $evolucion->cita->dentista->apellido_paterno }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Folio de Cita</div>
                <div class="info-value">#{{ str_pad($evolucion->cita_id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Nota SOAP</div>

        <div class="soap-box">
            <div class="soap-label">S — Subjetivo</div>
            <div class="soap-content">{{ $evolucion->subjetivo ?? 'Sin registro' }}</div>
        </div>

        <div class="soap-box">
            <div class="soap-label">O — Objetivo</div>
            <div class="soap-content">{{ $evolucion->objetivo ?? 'Sin registro' }}</div>
        </div>

        <div class="soap-box">
            <div class="soap-label">A — Análisis</div>
            <div class="soap-content">{{ $evolucion->analisis ?? 'Sin registro' }}</div>
        </div>

        <div class="soap-box">
            <div class="soap-label">P — Plan</div>
            <div class="soap-content">{{ $evolucion->plan ?? 'Sin registro' }}</div>
        </div>
    </div>

    @if (!empty($evolucion->tratamientos_completados))
        <div class="section">
            <div class="section-title">Tratamientos Realizados</div>
            <p style="font-size: 11px; color: #6b7280; margin-bottom: 10px;">
                Tratamientos marcados como completados durante esta consulta.
            </p>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($evolucion->tratamientos_completados as $id)
                        <tr>
                            <td>#{{ str_pad($id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td><span class="badge-completado">Completado</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if ($evolucion->cita->relationLoaded('receta') && $evolucion->cita->receta)
        <div class="section">
            <div class="section-title">Receta Médica</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Medicamento</th>
                        <th>Dosis</th>
                        <th>Frecuencia</th>
                        <th>Duración</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($evolucion->cita->receta->detalles as $detalle)
                        <tr>
                            <td>{{ $detalle->medicamento }}</td>
                            <td>{{ $detalle->dosis }}</td>
                            <td>{{ $detalle->frecuencia }}</td>
                            <td>{{ $detalle->duracion }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="signature-area">
                <div class="signature-box">
                    <div class="signature-line">
                        <div class="signature-label">Firma y Sello del Odontólogo</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="footer-note">
        <div class="footer-note-title">Nota Legal</div>
        <p>Este documento forma parte del expediente clínico del paciente y contiene información confidencial.
           Su divulgación sin autorización está prohibida conforme a la normativa aplicable.</p>
    </div>

@endsection
