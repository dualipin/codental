<?php

namespace App\Models;

use Database\Factories\EvolucionClinicaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvolucionClinica extends Model
{
    /** @use HasFactory<EvolucionClinicaFactory> */
    use HasFactory;

    protected $table = 'evolucion_clinicas';
    protected $guarded = [];

    protected $casts = [
        'tratamientos_completados' => 'array',
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function odontologo()
    {
        return $this->belongsTo(User::class, 'odontologo_id');
    }
}
