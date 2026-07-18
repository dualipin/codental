<?php

namespace Database\Factories;

use App\Models\HistoriaClinica;
use App\Models\Paciente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HistoriaClinica>
 */
class HistoriaClinicaFactory extends Factory
{
    protected $model = HistoriaClinica::class;

    public function definition(): array
    {
        $enfermedadesPrevias = fake()->randomElements(
            ['Diabetes', 'Hipertensión', 'Asma', 'Cardiopatías', 'Hepatitis', 'Artritis', 'Anemia', 'Epilepsia', 'Cáncer', 'Gastritis'],
            fake()->numberBetween(0, 4)
        );

        $habitosToxicos = [
            'tabaco' => fake()->boolean(30),
            'frecuencia_tabaco' => fake()->optional(0.5)->randomElement(['Ocasional', '1-5 diarios', '6-10 diarios', 'Más de 10 diarios']),
            'alcohol' => fake()->boolean(40),
            'frecuencia_alcohol' => fake()->optional(0.5)->randomElement(['Ocasional', 'Fines de semana', 'Diario']),
            'drogas' => fake()->boolean(5),
        ];

        $ginecoobstetricos = [
            'embarazo' => fake()->boolean(15),
            'semanas_gestion' => fake()->optional(0.3)->numberBetween(4, 40),
            'lactancia' => fake()->boolean(20),
            'metodo_anticonceptivo' => fake()->optional(0.4)->randomElement(['Orales', 'DIU', 'Inyección', 'Implante', 'Preservativo']),
        ];

        $estiloVida = [
            'deporte' => fake()->randomElement(['Ninguno', '1-2 veces/semana', '3-4 veces/semana', 'Diario']),
            'alimentacion' => fake()->randomElement(['Balanceada', 'Regular', 'Alta en azúcares', 'Vegetariana', 'Vegana']),
            'higiene_bucal' => fake()->randomElement(['Excelente', 'Buena', 'Regular', 'Mala']),
            'cepillado_diario' => fake()->randomElement(['1 vez', '2 veces', '3 veces', 'Más de 3']),
            'uso_hilo_dental' => fake()->boolean(40),
            'uso_enjuague' => fake()->boolean(50),
        ];

        $antecedentesBucodentales = [
            'ultima_visita' => fake()->randomElement(['Nunca', 'Menos de 6 meses', '6-12 meses', '1-2 años', 'Más de 2 años']),
            'cepillo' => fake()->randomElement(['Manual', 'Eléctrico']),
            'dolor_dental' => fake()->boolean(45),
            'sangrado_encias' => fake()->boolean(35),
            'sensibilidad' => fake()->boolean(40),
            'habitos' => fake()->randomElements(['Bruxismo', 'Onicofagia', 'Morder objetos', 'Succión digital', 'Respiración bucal'], fake()->numberBetween(0, 3)),
            'cirugias_orales' => fake()->boolean(20),
            'ortodoncia_previa' => fake()->boolean(15),
            'protesis' => fake()->boolean(10),
        ];

        $atm = [
            'clase_molar' => fake()->randomElement(['Clase I', 'Clase II', 'Clase III']),
            'clase_canina' => fake()->randomElement(['Clase I', 'Clase II', 'Clase III']),
            'overjet_mm' => fake()->randomFloat(1, 0, 8),
            'overbite_mm' => fake()->randomFloat(1, 0, 6),
            'chasquidos' => fake()->boolean(25),
            'dolor_atm' => fake()->boolean(15),
            'desviacion_apertura' => fake()->boolean(10),
            'limitacion_apertura' => fake()->boolean(5),
        ];

        return [
            'paciente_id' => Paciente::factory(),
            'antecedentes_hereditarios' => fake()->optional(0.8)->paragraph(),
            'alergias' => fake()->optional(0.5)->randomElement(['Penicilina', 'Aspirina', 'Ibuprofeno', 'Látex', 'Anestésicos locales', 'Ninguna conocida']),
            'medicacion_actual' => fake()->optional(0.4)->randomElement(['Antihipertensivos', 'Antidiabéticos', 'Anticoagulantes', 'Antidepresivos', 'Antiinflamatorios', 'Ninguna']),
            'nombre_medico' => fake()->optional(0.3)->name(),
            'telefono_medico' => fake()->optional(0.3)->numerify('##########'),
            'enfermedades_previas' => $enfermedadesPrevias,
            'otras_enfermedades' => fake()->optional(0.2)->sentence(),
            'habitos_toxicos' => $habitosToxicos,
            'grupo_sanguineo' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'ginecoobstetricos' => $ginecoobstetricos,
            'estilo_vida' => $estiloVida,
            'cirugias_hospitalizaciones' => fake()->optional(0.4)->paragraph(),
            'padecimiento_actual' => fake()->optional(0.7)->paragraph(),
            'interrogatorio_sistemas' => fake()->optional(0.6)->paragraph(),
            'examenes_laboratorio' => fake()->optional(0.3)->randomElement(['Biometría hemática', 'Química sanguínea', 'Tiempos de coagulación', 'Radiografía panorámica', 'Ninguno']),
            'antecedentes_bucodentales' => $antecedentesBucodentales,
            'atm' => $atm,
            'tejidos_blandos_duros' => fake()->optional(0.6)->paragraph(),
        ];
    }
}
