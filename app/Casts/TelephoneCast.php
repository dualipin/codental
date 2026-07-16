<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class TelephoneCast implements CastsAttributes
{
    /**
     * Al recuperar el teléfono de la base de datos
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value; // Se devuelve tal cual está guardado (9879871234)
    }

    /**
     * Al guardar el teléfono en la base de datos (Fuerza el formato XXXXXXXXXX)
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (empty($value)) {
            return null;
        }

        // 1. Deja solo los números (elimina +, espacios, guiones y paréntesis)
        $clean = preg_replace('/[^0-9]/', '', $value);

        // 2. Si trae más de 10 dígitos (por ejemplo prefijo de país), conserva solo los últimos 10.
        if (strlen($clean) > 10) {
            return substr($clean, -10);
        }

        // 3. Si tiene 10 o menos dígitos, guarda lo que haya limpio.
        return $clean;
    }
}
