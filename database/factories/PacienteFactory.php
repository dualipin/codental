<?php

namespace Database\Factories;

use App\Enums\EstadoCivilEnum;
use App\Enums\SexoEnum;
use App\Models\Paciente;
use Database\Factories\HistoriaClinicaFactory;
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
            'ocupacion' => fake()->jobTitle(),
            'direccion' => fake()->streetAddress(),
            'estado' => fake()->state(),
            'municipio' => fake()->city(),
            'telefono' => fake()->numerify('##########'),
            'correo_electronico' => fake()->optional()->safeEmail(),
            'religion' => fake()->optional()->randomElement(['Católica', 'Cristiana', 'Ninguna']),
            'verificado' => fake()->boolean(80),
        ];
    }

    public function conHistoriaClinica(): static
    {
        return $this->afterCreating(function (Paciente $paciente) {
            $paciente->historiaClinica()->create(
                HistoriaClinicaFactory::new()->definition()
            );
        });
    }
}
