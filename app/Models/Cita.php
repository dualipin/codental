<?php

namespace App\Models;

use App\Enums\EstatusCitaEnum;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $guarded = [];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    protected function casts(): array
    {
        return [
            'estatus' => EstatusCitaEnum::class,
        ];
    }
}
