<?php

namespace App\Enums;

enum EstatusCitaEnum: string
{
    case PENDIENTE = 'Pendiente';
    case CONFIRMADA = 'Confirmada';
    case CANCELADA = 'Cancelada';
    case REPROGRAMADA = 'Reprogramada';
}
