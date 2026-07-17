<?php

namespace Tests\Feature;

use App\Enums\UserRolEnum;
use App\Models\HistoriaClinica;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HistoriaClinicaAntecedentesMedicosTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_puede_ver_edicion_de_historia_clinica(): void
    {
        $usuario = User::factory()->create(['rol' => UserRolEnum::ADMINISTRADOR]);
        $paciente = Paciente::factory()->create();

        $response = $this->actingAs($usuario)->get(route('pacientes.historia-clinica.edit', $paciente));

        $response->assertOk();
        $response->assertSee('"component":"Pacientes\\/HistoriaClinica\\/Edit"', false);
    }

    public function test_admin_puede_actualizar_historia_clinica_completa(): void
    {
        $usuario = User::factory()->create(['rol' => UserRolEnum::ADMINISTRADOR]);
        $paciente = Paciente::factory()->create();

        $response = $this->actingAs($usuario)->patch(
            route('pacientes.historia-clinica.update', $paciente),
            [
                'antecedentes_hereditarios' => ' Diabetes  ',
                'alergias' => 'Penicilina',
                'medicacion_actual' => 'Losartan',
                'nombre_medico' => 'Dr. Perez',
                'telefono_medico' => '9931234567',
                'enfermedades_previas' => ['diabetes', 'hipertension'],
                'otras_enfermedades' => 'Hipotiroidismo',
                'habitos_toxicos' => [
                    'tabaco' => true,
                    'alcohol' => false,
                    'drogas' => false,
                    'frecuenciaConsumo' => 'Ocasional',
                ],
                'grupo_sanguineo' => 'O+',
                'ginecoobstetricos' => [
                    'embarazo' => false,
                    'tiempoGestacion' => '',
                    'lactancia' => false,
                    'mesesBebe' => '',
                ],
                'estilo_vida' => [
                    'actividadFisica' => 'Moderada',
                    'calidadDieta' => 'Buena',
                    'calidadHigiene' => 'Buena',
                ],
                'cirugias_hospitalizaciones' => 'Apendicectomia',
                'padecimiento_actual' => 'Dolor dental',
                'interrogatorio_sistemas' => 'Sin hallazgos',
                'examenes_laboratorio' => 'Biometria normal',
                'antecedentes_bucodentales' => [
                    'ultimaRevision' => '6 a 12 meses',
                    'motivoRevision' => 'Limpieza',
                    'auxiliaresLimpieza' => true,
                    'detalleAuxiliares' => 'Hilo dental',
                    'frecuenciaCepillado' => '3 veces al día',
                    'anestesiaLocal' => true,
                    'complicacionAnestesia' => false,
                    'detalleComplicacionAnestesia' => '',
                    'remedioCasero' => false,
                    'detalleRemedioCasero' => '',
                    'dolorMasticar' => true,
                    'detalleDolorMasticar' => 'Lado derecho',
                    'sangradoInflamacion' => true,
                    'detalleSangradoInflamacion' => 'Al cepillar',
                    'ulcerasBucales' => false,
                    'frecuenciaUlceras' => '',
                    'habitosOrales' => true,
                    'detalleHabitosOrales' => 'Bruxismo',
                ],
                'atm' => [
                    'malestarAbrirBocas' => true,
                    'malestarMovimientoLateral' => false,
                    'chasquidosCrepitaciones' => true,
                    'desviacionMandibula' => false,
                ],
                'tejidos_blandos_duros' => 'Sin alteraciones visibles',
            ],
        );

        $response->assertRedirect(route('pacientes.historia-clinica.edit', $paciente));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('historias_clinicas', [
            'paciente_id' => $paciente->id,
            'antecedentes_hereditarios' => 'Diabetes',
            'alergias' => 'Penicilina',
            'medicacion_actual' => 'Losartan',
            'nombre_medico' => 'Dr. Perez',
            'telefono_medico' => '9931234567',
            'otras_enfermedades' => 'Hipotiroidismo',
            'grupo_sanguineo' => 'O+',
            'cirugias_hospitalizaciones' => 'Apendicectomia',
            'padecimiento_actual' => 'Dolor dental',
            'interrogatorio_sistemas' => 'Sin hallazgos',
            'examenes_laboratorio' => 'Biometria normal',
            'tejidos_blandos_duros' => 'Sin alteraciones visibles',
        ]);

        $historiaClinica = HistoriaClinica::where('paciente_id', $paciente->id)->firstOrFail();

        $this->assertSame(['diabetes', 'hipertension'], $historiaClinica->enfermedades_previas);
        $this->assertTrue($historiaClinica->habitos_toxicos['tabaco']);
        $this->assertSame('Ocasional', $historiaClinica->habitos_toxicos['frecuenciaConsumo']);
        $this->assertSame('Moderada', $historiaClinica->estilo_vida['actividadFisica']);
        $this->assertSame('6 a 12 meses', $historiaClinica->antecedentes_bucodentales['ultimaRevision']);
        $this->assertTrue($historiaClinica->atm['malestarAbrirBocas']);
        $this->assertTrue($historiaClinica->atm['chasquidosCrepitaciones']);
    }

    public function test_recepcionista_no_puede_actualizar_historia_clinica(): void
    {
        $usuario = User::factory()->create(['rol' => UserRolEnum::RECEPCIONISTA]);
        $paciente = Paciente::factory()->create();

        $response = $this->actingAs($usuario)->patch(
            route('pacientes.historia-clinica.update', $paciente),
            [
                'antecedentes_hereditarios' => 'Hipertension',
            ],
        );

        $response->assertForbidden();
        $this->assertDatabaseMissing('historias_clinicas', [
            'paciente_id' => $paciente->id,
        ]);
    }

    public function test_recepcionista_no_puede_ver_edicion_de_historia_clinica(): void
    {
        $usuario = User::factory()->create(['rol' => UserRolEnum::RECEPCIONISTA]);
        $paciente = Paciente::factory()->create();

        $response = $this->actingAs($usuario)->get(route('pacientes.historia-clinica.edit', $paciente));

        $response->assertForbidden();
    }
}
