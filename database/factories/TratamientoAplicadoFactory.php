<?php

namespace Database\Factories;

use App\Models\Diente;
use App\Models\Paciente;
use App\Models\Tratamiento;
use App\Models\TratamientoAplicado;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TratamientoAplicado>
 */
class TratamientoAplicadoFactory extends Factory
{
    protected $model = TratamientoAplicado::class;

    public function definition(): array
    {
        $estado = fake()->randomElement(['planificado', 'en_proceso', 'completado', 'cancelado']);

        return [
            'paciente_id' => Paciente::factory(),
            'hallazgo_dental_id' => null,
            'tratamiento_id' => Tratamiento::factory(),
            'precio_tratamiento_id' => null,
            'diente_id' => Diente::factory(),
            'cara_dental_id' => null,
            'estado' => $estado,
            'fecha_planificada' => fake()->optional(0.7)->dateTimeBetween('now', '+1 month'),
            'fecha_realizado' => $estado === 'completado' ? fake()->dateTimeBetween('-1 month', 'now') : null,
            'odontologo_id' => User::factory(),
            'notas' => fake()->optional(0.5)->sentence(),
        ];
    }

    public function planificado(): static
    {
        return $this->state(['estado' => 'planificado']);
    }

    public function completado(): static
    {
        return $this->state([
            'estado' => 'completado',
            'fecha_realizado' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }
}
