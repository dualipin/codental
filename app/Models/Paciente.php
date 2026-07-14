<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Paciente extends Model
{
    protected $guarded = [];

    public function historiaClinica()
    {
        return $this->hasOne(HistoriaClinica::class);
    }

    public function consultas()
    {
        return $this->hasMany(Consulta::class);
    }
}