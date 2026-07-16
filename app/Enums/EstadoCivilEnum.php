<?php

namespace App\Enums;

enum EstadoCivilEnum:string
{
    case SOLTERO = 'Soltero(a)';
    case CASADO = 'Casado(a)';
    case DIVORCIADO = 'Divorciado(a)';
    case VIUDO = 'Viudo(a)';
}
