<?php

namespace Database\Factories;

use App\Models\User;
use App\UserRolEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),

            'nombre' => fake()->firstName(),
            'apellido_paterno' => fake()->lastName(),
            'apellido_materno' => fake()->lastName(),
            'sexo' => fake()->randomElement(['M', 'F']),
            'especialidad' => fake()->randomElement(['General', 'Ortodoncia', 'Endodoncia', 'Cirugía', 'Periodoncia']),
            'fecha_nacimiento' => fake()->date(),
            'estado_civil' => fake()->randomElement(['Soltero(a)', 'Casado(a)', 'Divorciado(a)', 'Viudo(a)']),
            'direccion' => fake()->streetAddress(),
            'estado' => fake()->state(),
            'municipio' => fake()->city(),
            'telefono' => fake()->numerify('##########'),
            'rol' => fake()->randomElement(UserRolEnum::cases()),

            'cedula' => fake()->optional()->numerify('########'),

            'foto_usuario' => null,

            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
