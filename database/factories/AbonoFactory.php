<?php

namespace Database\Factories;

use App\Models\Abono;
use App\Models\Paciente;
use App\Models\Presupuesto;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Abono>
 */
class AbonoFactory extends Factory
{
    protected $model = Abono::class;

    public function definition(): array
    {
        return [
            'paciente_id' => Paciente::factory(),
            'presupuesto_id' => Presupuesto::factory(),
            'registrado_por_id' => User::factory(),
            'monto' => fake()->randomFloat(2, 100, 3000),
            'metodo_pago' => fake()->randomElement(['efectivo', 'tarjeta', 'transferencia']),
            'fecha_pago' => fake()->dateTimeBetween('-1 month', 'now'),
            'referencia' => fake()->optional(0.4)->bothify('REF-####-????'),
            'estado' => fake()->randomElement(['pendiente', 'completado', 'cancelado']),
            'fecha_abono' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
