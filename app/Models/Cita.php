<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cita extends Model
{
    protected $guarded = [];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}
