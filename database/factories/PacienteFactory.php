<?php

namespace Database\Factories;

use App\Enums\EstadoCivilEnum;
use App\Enums\SexoEnum;
use App\Models\Paciente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Paciente>
 */
class PacienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->firstName(),
            'apellido_paterno' => fake()->lastName(),
            'apellido_materno' => fake()->lastName(),
            'sexo' => fake()->randomElement(SexoEnum::cases()),
            'fecha_nacimiento' => fake()->date(),
            'estado_civil' => fake()->randomElement(EstadoCivilEnum::cases()),
            'direccion' => fake()->streetAddress(),
            'estado' => fake()->state(),
            'municipio' => fake()->city(),
            'telefono' => fake()->numerify('##########'),
        ];
    }
}
