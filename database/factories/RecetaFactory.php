<?php

namespace Database\Factories;

use App\Models\Cita;
use App\Models\Receta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Receta>
 */
class RecetaFactory extends Factory
{
    protected $model = Receta::class;

    public function definition(): array
    {
        return [
            'cita_id' => Cita::factory(),
        ];
    }
}
