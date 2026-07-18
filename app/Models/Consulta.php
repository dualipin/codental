<?php

namespace App\Models;

use Database\Factories\ConsultaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consulta extends Model
{
    /** @use HasFactory<ConsultaFactory> */
    use HasFactory;

    protected $table = 'consultas';
    protected $guarded = [];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function odontologo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'odontologo_id');
    }

    public function cita(): BelongsTo
    {
        return $this->belongsTo(Cita::class, 'cita_id');
    }
}