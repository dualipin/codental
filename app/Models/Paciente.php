<?php

namespace App\Models;

use App\Casts\TelephoneCast;
use App\Enums\SexoEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Paciente extends Model
{
    /** @uses HasFactory<PacienteFactory> */
    use HasFactory;


    protected $table = 'pacientes';
    protected $guarded = [];

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class, 'paciente_id');
    }

    public function consultas(): HasMany
    {
        return $this->hasMany(Consulta::class, 'paciente_id');
    }

    public function presupuestos(): HasMany
    {
        return $this->hasMany(Presupuesto::class, 'paciente_id');
    }

    public function historiaClinica(): HasOne
    {
        return $this->hasOne(HistoriaClinica::class, 'paciente_id');
    }

    protected function casts(): array
    {
        return [
            'telefono' => TelephoneCast::class,
            'sexo' => SexoEnum::class
        ];
    }
}