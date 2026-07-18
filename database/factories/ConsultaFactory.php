<?php

namespace Database\Factories;

use App\Models\Cita;
use App\Models\Consulta;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Consulta>
 */
class ConsultaFactory extends Factory
{
    protected $model = Consulta::class;

    public function definition(): array
    {
        return [
            'paciente_id' => Paciente::factory(),
            'odontologo_id' => User::factory(),
            'cita_id' => Cita::factory(),
            'fecha_consulta' => fake()->dateTimeBetween('-1 month', 'now'),
            'motivo_consulta' => fake()->randomElement([
                'Consulta general', 'Dolor de muela', 'Limpieza dental',
                'Revisión de rutina', 'Tratamiento de conducto',
            ]),
            'peso' => fake()->randomFloat(1, 50, 120),
            'estatura' => fake()->randomFloat(2, 1.50, 1.90),
            'temperatura' => fake()->randomFloat(1, 36.0, 37.5),
            'frecuencia_cardiaca' => fake()->numberBetween(60, 100),
            'presion_arterial' => fake()->randomFloat(0, 110, 140),
            'nota_evolucion' => fake()->randomElement([
                'Paciente sin novedades, continúa con tratamiento indicado.',
                'Se observa mejoría significativa en la zona tratada.',
                'Paciente refiere dolor leve post-operatorio, se indica analgesia.',
                'Se realiza curación y se agenda próxima cita para revisión.',
                'Paciente presenta buena evolución, se da de alta del tratamiento.',
            ]),
        ];
    }
}
