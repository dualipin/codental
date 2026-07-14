<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriaClinica extends Model
{
    protected $table = 'historias_clinicas';
    protected $guarded = [];

    // Esto convierte los campos JSON de MySQL a Arrays de PHP
    protected $casts = [
        'enfermedades_previas' => 'array',
        'habitos_toxicos' => 'array',
        'ginecoobstetricos' => 'array',
        'estilo_vida' => 'array',
        'antecedentes_bucodentales' => 'array',
        'atm' => 'array',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}