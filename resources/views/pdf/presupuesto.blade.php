@extends('layouts.pdf')
@section('document-title', 'Presupuesto')

@section('document-styles')
    <style>
        .title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 18px;
        }

        .section {
            margin-bottom: 18px;
        }

        .section-title {
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background: #f3f4f6;
        }

        .summary {
            width: 100%;
            margin-top: 10px;
        }

        .summary td {
            padding: 4px 0;
        }

        .summary .label {
            width: 180px;
            font-weight: 700;
        }
    </style>
@endsection

@section('document-content')
    <div class="title">Presupuesto</div>

    <div class="section">
        <div class="section-title">Datos generales</div>
        <table class="table">
            <tr>
                <th>Folio</th>
                <td>#{{ str_pad($presupuesto->id, 6, '0', STR_PAD_LEFT) }}</td>
                <th>Estado</th>
                <td>{{ ucfirst($presupuesto->estado) }}</td>
            </tr>
            <tr>
                <th>Paciente</th>
                <td colspan="3">{{ $presupuesto->paciente?->nombre }} {{ $presupuesto->paciente?->apellido_paterno }} {{ $presupuesto->paciente?->apellido_materno ?? '' }}</td>
            </tr>
            <tr>
                <th>Dentista</th>
                <td colspan="3">Dr(a). {{ $presupuesto->dentista?->nombre }} {{ $presupuesto->dentista?->apellido_paterno }} {{ $presupuesto->dentista?->apellido_materno ?? '' }}</td>
            </tr>
            <tr>
                <th>Emisión</th>
                <td>{{ $presupuesto->fecha_emision?->format('d/m/Y') }}</td>
                <th>Vencimiento</th>
                <td>{{ $presupuesto->fecha_vencimiento?->format('d/m/Y') }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Detalles</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Tratamiento</th>
                    <th>Precio</th>
                    <th>Descuento</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($presupuesto->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->tratamientoCatalogo?->nombre ?? 'Tratamiento' }}</td>
                        <td>${{ number_format((float) $detalle->precio_congelado, 2) }}</td>
                        <td>${{ number_format((float) $detalle->monto_descuento, 2) }}</td>
                        <td>${{ number_format((float) $detalle->precio_congelado - (float) $detalle->monto_descuento, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <table class="summary">
        <tr>
            <td class="label">Total presupuesto</td>
            <td>${{ number_format((float) $presupuesto->monto, 2) }}</td>
        </tr>
    </table>
@endsection
