<?php

namespace Database\Factories;

use App\Models\Receta;
use App\Models\RecetaDetalle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RecetaDetalle>
 */
class RecetaDetalleFactory extends Factory
{
    protected $model = RecetaDetalle::class;

    public function definition(): array
    {
        return [
            'receta_id' => Receta::factory(),
            'medicamento' => fake()->randomElement([
                'Amoxicilina 500mg',
                'Ibuprofeno 600mg',
                'Ketorolaco 10mg',
                'Metronidazol 250mg',
                'Clindamicina 300mg',
                'Naproxeno 500mg',
                'Acetaminofén 500mg',
                'Enjuague de Clorhexidina 0.12%',
                'Gel de Flúor',
                'Benzocaína tópica',
            ]),
            'dosis' => fake()->randomElement(['1 tableta', '1 cucharada', '2 tabletas', '1 aplicación']),
            'frecuencia' => fake()->randomElement(['Cada 8 horas', 'Cada 12 horas', 'Cada 6 horas', 'Cada 24 horas', 'Cada 4 horas']) . fake()->randomElement([' durante 3 días', ' durante 5 días', ' durante 7 días', ' por 10 días']),
            'duracion' => fake()->randomElement(['3 días', '5 días', '7 días', '10 días', '14 días']),
        ];
    }
}
