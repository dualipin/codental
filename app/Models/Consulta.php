<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $guarded = [];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function odontograma()
    {
        return $this->hasOne(Odontograma::class);
    }
}