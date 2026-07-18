<?php

namespace Database\Factories;

use App\Enums\TipoSeguimientoOdontogramaEnum;
use App\Models\Odontograma;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Odontograma>
 */
class OdontogramaFactory extends Factory
{
    protected $model = Odontograma::class;

    public function definition(): array
    {
        return [
            'paciente_id' => Paciente::factory(),
            'odontologo_id' => User::factory(),
            'cita_id' => null,
            'fecha' => fake()->dateTimeBetween('-3 months', 'now'),
            'tipo_seguimiento' => fake()->randomElement(TipoSeguimientoOdontogramaEnum::cases()),
            'observaciones' => fake()->optional(0.6)->sentence(),
        ];
    }

    public function evaluacionInicial(): static
    {
        return $this->state([
            'tipo_seguimiento' => TipoSeguimientoOdontogramaEnum::EVALUACION_INICIAL,
        ]);
    }
}
