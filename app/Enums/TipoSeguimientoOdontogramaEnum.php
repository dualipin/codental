<?php

namespace App\Enums;

enum TipoSeguimientoOdontogramaEnum: string
{
    case EVALUACION_INICIAL = 'evaluacion_inicial';
    case SEGUIMIENTO = 'seguimiento';
    case ALTA = 'alta';
    case REEVALUACION = 'reevaluacion';
}
