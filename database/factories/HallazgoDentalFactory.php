<?php

namespace Database\Factories;

use App\Models\HallazgoDental;
use App\Models\Odontograma;
use App\Models\Diente;
use App\Models\Enfermedad;
use App\Models\CarasDentales;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HallazgoDental>
 */
class HallazgoDentalFactory extends Factory
{
    protected $model = HallazgoDental::class;

    public function definition(): array
    {
        $enfermedad = Enfermedad::inRandomOrder()->first() ?? Enfermedad::factory();

        return [
            'odontograma_id' => Odontograma::factory(),
            'diente_id' => Diente::inRandomOrder()->first() ?? Diente::factory(),
            'cara_dental_id' => fake()->boolean(60)
                ? (CarasDentales::inRandomOrder()->first()?->id ?? CarasDentales::factory())
                : null,
            'enfermedad_id' => $enfermedad,
            'estado' => fake()->randomElement(['activo', 'activo', 'activo', 'resuelto', 'descartado']),
            'en_plan' => fake()->boolean(25),
            'notas' => fake()->optional(0.5)->sentence(),
        ];
    }

    public function activo(): static
    {
        return $this->state(['estado' => 'activo']);
    }
}
