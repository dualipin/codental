<?php

namespace Database\Factories;

use App\Enums\EstatusCitaEnum;
use App\Models\Cita;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cita>
 */
class CitaFactory extends Factory
{
    protected $model = Cita::class;

    public function definition(): array
    {
        $startHour = fake()->numberBetween(8, 17);
        $startMinute = fake()->randomElement([0, 15, 30, 45]);
        $daysOffset = fake()->numberBetween(-5, 5);

        $fechaInicio = now()->addDays($daysOffset)->setTime($startHour, $startMinute, 0);
        $fechaFin = (clone $fechaInicio)->addMinutes(fake()->randomElement([30, 45, 60]));

        $motivos = [
            'Consulta general',
            'Dolor de muela',
            'Limpieza dental',
            'Revisión de rutina',
            'Tratamiento de conducto',
            'Extracción de muela del juicio',
            'Colocación de brackets',
            'Blanqueamiento dental',
            'Urgencia dental',
            'Valoración inicial',
            'Seguimiento de tratamiento',
            'Reparación de corona',
        ];

        return [
            'paciente_id' => Paciente::factory(),
            'dentista_id' => User::factory(),
            'creado_por_id' => User::factory(),
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estatus' => $daysOffset < 0
                ? EstatusCitaEnum::FINALIZADO
                : fake()->randomElement([EstatusCitaEnum::PENDIENTE, EstatusCitaEnum::CONFIRMADA]),
            'motivo' => fake()->randomElement($motivos),
        ];
    }

    public function future(): static
    {
        return $this->state(function () {
            $startHour = fake()->numberBetween(8, 17);
            $startMinute = fake()->randomElement([0, 15, 30, 45]);

            $fechaInicio = now()->addDays(fake()->numberBetween(1, 5))->setTime($startHour, $startMinute, 0);
            $fechaFin = (clone $fechaInicio)->addMinutes(fake()->randomElement([30, 45, 60]));

            return [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'estatus' => fake()->randomElement([EstatusCitaEnum::PENDIENTE, EstatusCitaEnum::CONFIRMADA]),
            ];
        });
    }

    public function past(): static
    {
        return $this->state(function () {
            $daysAgo = fake()->numberBetween(1, 30);

            $fechaInicio = now()->subDays($daysAgo)->setTime(fake()->numberBetween(8, 17), fake()->randomElement([0, 15, 30, 45]), 0);
            $fechaFin = (clone $fechaInicio)->addMinutes(fake()->randomElement([30, 45, 60]));

            return [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'estatus' => EstatusCitaEnum::FINALIZADO,
            ];
        });
    }
}
