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
        return $value; // Se devuelve tal cual está guardado (529879871234)
    }

    /**
     * Al guardar el teléfono en la base de datos (Fuerza el formato 52XXXXXXXXXX)
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (empty($value)) {
            return null;
        }

        // 1. Deja solo los números (elimina +, espacios, guiones y paréntesis)
        $clean = preg_replace('/[^0-9]/', '', $value);

        // 2. Si el usuario ya escribió el '52' al inicio, lo dejamos igual
        if (str_starts_with($clean, '52') && strlen($clean) === 12) {
            return $clean;
        }

        // 3. Si introdujo solo los 10 dígitos locales, le anteponemos el '52'
        if (strlen($clean) === 10) {
            return '52' . $clean;
        }

        // 4. Si no cumple ninguna (ej. número incompleto), guarda lo que haya o limpia
        return $clean;
    }
}