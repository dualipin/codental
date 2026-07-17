<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TratamientoCatalogo extends Model
{
    protected $fillable = [
        'codigo',
        'nombre',
        'precio_base_actual',
    ];
}
