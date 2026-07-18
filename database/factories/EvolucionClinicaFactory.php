<?php

namespace Database\Factories;

use App\Models\Cita;
use App\Models\EvolucionClinica;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EvolucionClinica>
 */
class EvolucionClinicaFactory extends Factory
{
    protected $model = EvolucionClinica::class;

    public function definition(): array
    {
        return [
            'cita_id' => Cita::factory(),
            'paciente_id' => Paciente::factory(),
            'odontologo_id' => User::factory(),
            'subjetivo' => fake()->randomElement([
                'Paciente refiere dolor leve en la zona tratada, especialmente al masticar.',
                'Paciente menciona sensibilidad al frío que ha disminuido desde la última visita.',
                'Paciente reporta mejoría significativa, sin molestias desde el último tratamiento.',
                'Paciente comenta que ha tenido sangrado leve al cepillarse.',
                'Paciente refiere molestia en la articulación temporomandibular al abrir la boca.',
            ]),
            'objetivo' => fake()->randomElement([
                'Se observa tejido gingival con coloración rosada, sin signos de inflamación.',
                'Se aprecia caries en esmalte del diente tratado, sin afectación pulpar aparente.',
                'Se observa correcta oclusión y alineación dental. Tejidos blandos sin alteraciones.',
                'Se detecta placa bacteriana moderada en zona posterior. Encía con leve eritema.',
                'Se observa correcta integración de la corona, sin filtración aparente.',
            ]),
            'analisis' => 'Se evalúa el caso y se determina continuar con el plan de tratamiento establecido.',
            'plan' => fake()->randomElement([
                'Continuar con el tratamiento. Próxima cita en 2 semanas.',
                'Aplicar flúor y recomendar higiene bucal exhaustiva.',
                'Referir a endodoncia para evaluación del diente afectado.',
                'Programar extracción en la próxima sesión.',
                'Dar seguimiento en 1 mes para evaluar evolución.',
            ]),
        ];
    }
}
