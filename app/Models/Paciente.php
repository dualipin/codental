<?php

namespace App\Models;

use App\Casts\TelephoneCast;
use App\Enums\SexoEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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

    public function odontogramas(): HasMany
    {
        return $this->hasMany(Odontograma::class, 'paciente_id');
    }

    public function evolucionesClinicas(): HasMany
    {
        return $this->hasMany(EvolucionClinica::class, 'paciente_id');
    }

    public function recetas(): HasManyThrough
    {
        return $this->hasManyThrough(Receta::class, Cita::class, 'paciente_id', 'cita_id');
    }

    public function scopeNeedsFollowUp($query)
    {
        // Pacientes cuya última cita completada fue hace más de 6 meses
        // o tienen tratamientos planificados/en progreso pero sin citas futuras
        return $query->whereHas('citas', function ($q) {
            $q->where('estatus', \App\Enums\EstatusCitaEnum::FINALIZADO->value)
              ->where('fecha_inicio', '<', now()->subMonths(6));
        })->orWhere(function ($q) {
            // Asumiendo que hay forma de ver tratamientos. 
            // Usaremos la relación con citas para ver si no hay citas futuras
            $q->whereDoesntHave('citas', function ($q2) {
                $q2->where('fecha_inicio', '>=', now());
            });
        });
    }

    protected function casts(): array
    {
        return [
            'telefono' => TelephoneCast::class,
            'sexo' => SexoEnum::class,
            'fecha_nacimiento' => 'date',
        ];
    }
}
