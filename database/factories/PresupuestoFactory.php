<?php

namespace Database\Factories;

use App\Models\Paciente;
use App\Models\Presupuesto;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Presupuesto>
 */
class PresupuestoFactory extends Factory
{
    protected $model = Presupuesto::class;

    public function definition(): array
    {
        return [
            'paciente_id' => Paciente::factory(),
            'dentista_id' => User::factory(),
            'fecha_emision' => fake()->dateTimeBetween('-1 month', 'now'),
            'fecha_vencimiento' => fake()->dateTimeBetween('now', '+1 month'),
            'monto' => fake()->randomFloat(2, 500, 15000),
            'estado' => fake()->randomElement(['pendiente', 'aprobado', 'rechazado']),
        ];
    }

    public function aprobado(): static
    {
        return $this->state(['estado' => 'aprobado']);
    }

    public function pendiente(): static
    {
        return $this->state(['estado' => 'pendiente']);
    }
}
