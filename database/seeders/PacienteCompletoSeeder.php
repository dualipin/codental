<?php

namespace Database\Seeders;

use App\Enums\EstatusCitaEnum;
use App\Enums\TipoSeguimientoOdontogramaEnum;
use App\Enums\UserRolEnum;
use App\Models\Abono;
use App\Models\Cita;
use App\Models\Consulta;
use App\Models\Diente;
use App\Models\Enfermedad;
use App\Models\EvolucionClinica;
use App\Models\HallazgoDental;
use App\Models\Odontograma;
use App\Models\Paciente;
use App\Models\CarasDentales;
use App\Models\Presupuesto;
use App\Models\PresupuestoDetalle;
use App\Models\Receta;
use App\Models\RecetaDetalle;
use App\Models\Tratamiento;
use App\Models\TratamientoAplicado;
use App\Models\TratamientoCatalogo;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PacienteCompletoSeeder extends Seeder
{
    use WithoutModelEvents;

    private const int NUM_PACIENTES = 10;

    private const array MOTIVOS_CITA = [
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

    private const array TRATAMIENTOS_CATALOGO = [
        ['codigo' => 'CONS', 'nombre' => 'Consulta general', 'precio' => 500],
        ['codigo' => 'LIMP', 'nombre' => 'Limpieza dental (profilaxis)', 'precio' => 800],
        ['codigo' => 'REV', 'nombre' => 'Revisión de rutina', 'precio' => 350],
        ['codigo' => 'ENDOD', 'nombre' => 'Tratamiento de conducto (endodoncia)', 'precio' => 3500],
        ['codigo' => 'EXT', 'nombre' => 'Extracción simple', 'precio' => 1200],
        ['codigo' => 'EXTC', 'nombre' => 'Extracción de cordal (cirugía)', 'precio' => 2500],
        ['codigo' => 'BLANQ', 'nombre' => 'Blanqueamiento dental', 'precio' => 4500],
        ['codigo' => 'CARIES', 'nombre' => 'Resina (obturación)', 'precio' => 900],
        ['codigo' => 'CORONA', 'nombre' => 'Corona dental', 'precio' => 5500],
        ['codigo' => 'PUENTE', 'nombre' => 'Puente dental (3 piezas)', 'precio' => 12000],
        ['codigo' => 'ORTOD', 'nombre' => 'Ortodoncia (brackets)', 'precio' => 18000],
        ['codigo' => 'RX', 'nombre' => 'Radiografía periapical', 'precio' => 250],
        ['codigo' => 'RXPA', 'nombre' => 'Radiografía panorámica', 'precio' => 600],
        ['codigo' => 'FLUOR', 'nombre' => 'Aplicación de flúor', 'precio' => 350],
        ['codigo' => 'SELL', 'nombre' => 'Selladores de fosetas y fisuras', 'precio' => 400],
    ];

    private const array TRATAMIENTOS = [
        ['nombre' => 'Obturación con resina', 'descripcion' => 'Restauración dental con resina compuesta'],
        ['nombre' => 'Extracción dental', 'descripcion' => 'Extracción de pieza dental'],
        ['nombre' => 'Endodoncia', 'descripcion' => 'Tratamiento de conducto radicular'],
        ['nombre' => 'Corona protésica', 'descripcion' => 'Colocación de corona dental'],
        ['nombre' => 'Limpieza profunda', 'descripcion' => 'Raspado y alisado radicular'],
        ['nombre' => 'Blanqueamiento', 'descripcion' => 'Blanqueamiento dental ambulatorio'],
        ['nombre' => 'Aplicación de flúor', 'descripcion' => 'Aplicación tópica de flúor'],
        ['nombre' => 'Sellador dental', 'descripcion' => 'Aplicación de selladores'],
        ['nombre' => 'Incrustación', 'descripcion' => 'Incrustación dental'],
        ['nombre' => 'Extracción de cordal', 'descripcion' => 'Extracción quirúrgica de tercer molar'],
    ];

    private const array MEDICAMENTOS_RECETA = [
        ['medicamento' => 'Amoxicilina 500mg', 'dosis' => '1 tableta', 'frecuencia' => 'Cada 8 horas por 7 días', 'duracion' => '7 días'],
        ['medicamento' => 'Ibuprofeno 600mg', 'dosis' => '1 tableta', 'frecuencia' => 'Cada 8 horas por 5 días', 'duracion' => '5 días'],
        ['medicamento' => 'Ketorolaco 10mg', 'dosis' => '1 tableta', 'frecuencia' => 'Cada 6 horas por 3 días', 'duracion' => '3 días'],
        ['medicamento' => 'Metronidazol 250mg', 'dosis' => '1 tableta', 'frecuencia' => 'Cada 8 horas por 7 días', 'duracion' => '7 días'],
        ['medicamento' => 'Clindamicina 300mg', 'dosis' => '1 cápsula', 'frecuencia' => 'Cada 8 horas por 7 días', 'duracion' => '7 días'],
        ['medicamento' => 'Enjuague Clorhexidina 0.12%', 'dosis' => '10ml', 'frecuencia' => 'Cada 12 horas por 10 días', 'duracion' => '10 días'],
        ['medicamento' => 'Gel de Flúor', 'dosis' => 'Aplicación tópica', 'frecuencia' => 'Una vez al día por 14 días', 'duracion' => '14 días'],
        ['medicamento' => 'Naproxeno 500mg', 'dosis' => '1 tableta', 'frecuencia' => 'Cada 12 horas por 5 días', 'duracion' => '5 días'],
    ];

    public function run(): void
    {
        $dentistas = $this->getOrCreateUsers(UserRolEnum::DENTISTA, 3);
        $recepcionistas = $this->getOrCreateUsers(UserRolEnum::RECEPCIONISTA, 2);

        $dientes = Diente::all();
        $enfermedades = Enfermedad::all();
        $carasDentalesIds = CarasDentales::pluck('id');

        if ($dientes->isEmpty() || $enfermedades->isEmpty() || $carasDentalesIds->isEmpty()) {
            $this->command?->warn('Ejecuta primero DienteSeeder, EnfermedadSeeder y CarasDentalesSeeder.');

            return;
        }

        $this->seedCatalogoTratamientos();
        $tratamientos = $this->seedTratamientos();
        $tratamientosCatalogo = TratamientoCatalogo::all();
        $carasPorDiente = $this->buildCarasPorDiente();

        $this->command?->info('Creando pacientes con expedientes completos...');

        $pacientes = Paciente::factory(self::NUM_PACIENTES)
            ->conHistoriaClinica()
            ->create();

        foreach ($pacientes as $paciente) {
            $dentista = $dentistas->random();
            $recepcionista = $recepcionistas->random();

            $citas = $this->crearCitas($paciente, $dentista, $recepcionista);
            $odontograma = $this->crearOdontograma($paciente, $dentista, $dientes, $enfermedades, $carasDentalesIds);

            $this->crearTratamientosAplicados(
                $paciente, $odontograma, $dentista,
                $dientes, $tratamientos, $carasPorDiente,
            );
            $this->crearPresupuesto($paciente, $dentista, $tratamientosCatalogo);
            $this->crearRecetas($citas['finalizadas']);
        }

        $this->command?->info(self::NUM_PACIENTES . ' pacientes creados con expedientes completos.');
    }

    private function seedCatalogoTratamientos(): void
    {
        if (TratamientoCatalogo::count() > 0) {
            return;
        }

        $now = now();
        $data = array_map(fn (array $item) => [
            'codigo' => $item['codigo'],
            'nombre' => $item['nombre'],
            'precio_base_actual' => $item['precio'],
            'created_at' => $now,
            'updated_at' => $now,
        ], self::TRATAMIENTOS_CATALOGO);

        TratamientoCatalogo::insert($data);
    }

    private function seedTratamientos(): \Illuminate\Support\Collection
    {
        if (Tratamiento::count() > 0) {
            return Tratamiento::all();
        }

        $now = now();
        $data = array_map(fn (array $item) => [
            'nombre' => $item['nombre'],
            'descripcion' => $item['descripcion'],
            'created_at' => $now,
            'updated_at' => $now,
        ], self::TRATAMIENTOS);

        Tratamiento::insert($data);

        return Tratamiento::all();
    }

    private function buildCarasPorDiente(): array
    {
        $map = [];
        $caras = CarasDentales::all();
        $dientes = Diente::all();

        foreach ($dientes as $diente) {
            $carasValidas = $caras->filter(function ($cara) use ($diente) {
                if ($diente->posicion === 'posterior') {
                    return !in_array($cara->nombre, ['Incisal']);
                }

                return !in_array($cara->nombre, ['Oclusal']);
            });

            $map[$diente->id] = $carasValidas->pluck('id')->toArray();
        }

        return $map;
    }

    /**
     * @return array{citas: \Illuminate\Support\Collection, finalizadas: \Illuminate\Support\Collection}
     */
    private function crearCitas(Paciente $paciente, User $dentista, User $recepcionista): array
    {
        $numCitas = random_int(2, 4);
        $todas = collect();
        $finalizadas = collect();

        for ($i = 0; $i < $numCitas; $i++) {
            $daysOffset = random_int(-5, 5);
            $startHour = random_int(8, 17);
            $startMinute = random_int(0, 3) * 15;

            $fechaInicio = now()->addDays($daysOffset)->setTime($startHour, $startMinute, 0);
            $duracion = random_int(2, 4) * 15;
            $fechaFin = (clone $fechaInicio)->addMinutes($duracion);

            $estatus = $daysOffset < 0
                ? EstatusCitaEnum::FINALIZADO
                : fake()->randomElement([EstatusCitaEnum::PENDIENTE, EstatusCitaEnum::CONFIRMADA]);

            $cita = Cita::create([
                'paciente_id' => $paciente->id,
                'dentista_id' => $dentista->id,
                'creado_por_id' => $recepcionista->id,
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'estatus' => $estatus,
                'motivo' => fake()->randomElement(self::MOTIVOS_CITA),
            ]);

            $todas->push($cita);

            if ($estatus === EstatusCitaEnum::FINALIZADO) {
                Consulta::create([
                    'paciente_id' => $paciente->id,
                    'odontologo_id' => $dentista->id,
                    'cita_id' => $cita->id,
                    'fecha_consulta' => $fechaInicio->toDateString(),
                    'motivo_consulta' => $cita->motivo,
                    'peso' => fake()->randomFloat(1, 50, 120),
                    'estatura' => fake()->randomFloat(1, 1.50, 1.90),
                    'temperatura' => fake()->randomFloat(1, 36.0, 37.5),
                    'frecuencia_cardiaca' => random_int(60, 100),
                    'presion_arterial' => fake()->randomFloat(0, 110, 140),
                    'nota_evolucion' => fake()->randomElement([
                        'Paciente sin novedades, continúa con tratamiento indicado.',
                        'Se observa mejoría significativa en la zona tratada.',
                        'Paciente refiere dolor leve post-operatorio, se indica analgesia.',
                        'Se realiza curación y se agenda próxima cita para revisión.',
                        'Paciente presenta buena evolución, se da de alta del tratamiento.',
                        'Se ajusta ortodoncia, paciente tolera procedimiento sin molestias.',
                    ]),
                ]);

                EvolucionClinica::create([
                    'cita_id' => $cita->id,
                    'paciente_id' => $paciente->id,
                    'odontologo_id' => $dentista->id,
                    'subjetivo' => fake()->randomElement([
                        'Paciente refiere dolor leve en la zona tratada, especialmente al masticar.',
                        'Paciente menciona sensibilidad al frío que ha disminuido desde la última visita.',
                        'Paciente reporta mejoría significativa, sin molestias desde el último tratamiento.',
                        'Paciente comenta que ha tenido sangrado leve al cepillarse.',
                    ]),
                    'objetivo' => fake()->randomElement([
                        'Se observa tejido gingival con coloración rosada, sin signos de inflamación.',
                        'Se aprecia caries en esmalte del diente tratado, sin afectación pulpar aparente.',
                        'Se observa correcta oclusión y alineación dental. Tejidos blandos sin alteraciones.',
                        'Se detecta placa bacteriana moderada en zona posterior. Encía con leve eritema.',
                    ]),
                    'analisis' => 'Se evalúa el caso y se determina continuar con el plan de tratamiento establecido.',
                    'plan' => fake()->randomElement([
                        'Continuar con el tratamiento. Próxima cita en 2 semanas.',
                        'Aplicar flúor y recomendar higiene bucal exhaustiva.',
                        'Referir a endodoncia para evaluación del diente afectado.',
                        'Programar extracción en la próxima sesión.',
                        'Dar seguimiento en 1 mes para evaluar evolución.',
                    ]),
                ]);

                $finalizadas->push($cita);
            }
        }

        return ['citas' => $todas, 'finalizadas' => $finalizadas];
    }

    private function crearOdontograma(
        Paciente $paciente,
        User $dentista,
        iterable $dientes,
        iterable $enfermedades,
        iterable $carasDentalesIds,
    ): Odontograma {
        $odontograma = Odontograma::create([
            'paciente_id' => $paciente->id,
            'odontologo_id' => $dentista->id,
            'fecha' => now()->subDays(random_int(1, 30))->toDateString(),
            'tipo_seguimiento' => TipoSeguimientoOdontogramaEnum::EVALUACION_INICIAL,
            'observaciones' => fake()->optional(0.7)->sentence(),
        ]);

        $numHallazgos = random_int(4, 10);
        $dientesSeleccionados = $dientes->random($numHallazgos);

        foreach ($dientesSeleccionados as $diente) {
            $enfermedad = $enfermedades->random();
            $usaCara = $enfermedad->nombre !== 'Ausente' && $enfermedad->nombre !== 'Extraído' && fake()->boolean(65);

            $odontograma->hallazgos()->create([
                'diente_id' => $diente->id,
                'cara_dental_id' => $usaCara ? $carasDentalesIds->random() : null,
                'enfermedad_id' => $enfermedad->id,
                'estado' => fake()->randomElement(['activo', 'activo', 'activo', 'resuelto']),
                'en_plan' => fake()->boolean(30),
                'notas' => fake()->optional(0.4)->sentence(),
            ]);
        }

        return $odontograma;
    }

    private function crearTratamientosAplicados(
        Paciente $paciente,
        Odontograma $odontograma,
        User $dentista,
        iterable $dientes,
        iterable $tratamientos,
        array $carasPorDiente,
    ): void {
        $hallazgosEnPlan = $odontograma->hallazgos()->where('en_plan', true)->get();

        foreach ($hallazgosEnPlan as $hallazgo) {
            $tratamiento = $tratamientos->random();
            $carasValidas = $carasPorDiente[$hallazgo->diente_id] ?? [];

            TratamientoAplicado::create([
                'paciente_id' => $paciente->id,
                'hallazgo_dental_id' => $hallazgo->id,
                'tratamiento_id' => $tratamiento->id,
                'diente_id' => $hallazgo->diente_id,
                'cara_dental_id' => $hallazgo->cara_dental_id && in_array($hallazgo->cara_dental_id, $carasValidas)
                    ? $hallazgo->cara_dental_id
                    : (!empty($carasValidas) ? fake()->randomElement($carasValidas) : null),
                'estado' => fake()->randomElement(['planificado', 'planificado', 'en_proceso', 'completado']),
                'fecha_planificada' => fake()->optional(0.8)->dateTimeBetween('now', '+1 month'),
                'fecha_realizado' => null,
                'odontologo_id' => $dentista->id,
                'notas' => fake()->optional(0.5)->sentence(),
            ]);
        }
    }

    private function crearPresupuesto(Paciente $paciente, User $dentista, iterable $catalogos): void
    {
        $presupuesto = Presupuesto::create([
            'paciente_id' => $paciente->id,
            'dentista_id' => $dentista->id,
            'fecha_emision' => now()->subDays(random_int(1, 15)),
            'fecha_vencimiento' => now()->addMonth(),
            'monto' => 0,
            'estado' => fake()->randomElement(['pendiente', 'aprobado', 'rechazado']),
        ]);

        $numDetalles = random_int(1, 4);
        $itemsSeleccionados = $catalogos->random($numDetalles);
        $montoTotal = 0;

        foreach ($itemsSeleccionados as $item) {
            $descuento = fake()->boolean(30) ? fake()->randomFloat(2, 0, $item->precio_base_actual * 0.2) : 0;
            $precioCongelado = $item->precio_base_actual;

            PresupuestoDetalle::create([
                'presupuesto_id' => $presupuesto->id,
                'tratamiento_catalogo_id' => $item->id,
                'referencia_odontograma_id' => fake()->optional(0.6)->numberBetween(11, 48),
                'precio_congelado' => $precioCongelado,
                'monto_descuento' => $descuento,
                'justificacion_descuento' => $descuento > 0
                    ? fake()->randomElement(['Descuento por pronto pago', 'Promoción del mes', 'Paciente recurrente'])
                    : null,
                'estado_tratamiento' => fake()->randomElement(['pendiente', 'en_progreso', 'completado']),
            ]);

            $montoTotal += $precioCongelado - $descuento;
        }

        $presupuesto->update(['monto' => $montoTotal]);

        if ($presupuesto->estado === 'aprobado' && fake()->boolean(60)) {
            $this->crearAbonos($presupuesto, $paciente, $dentista);
        }
    }

    private function crearAbonos(Presupuesto $presupuesto, Paciente $paciente, User $dentista): void
    {
        $numAbonos = random_int(1, 3);
        $montoRestante = $presupuesto->monto;

        for ($i = 0; $i < $numAbonos && $montoRestante > 0; $i++) {
            $esUltimo = $i === $numAbonos - 1;
            $monto = $esUltimo
                ? $montoRestante
                : fake()->randomFloat(2, $montoRestante * 0.2, $montoRestante * 0.6);

            $monto = min($monto, $montoRestante);
            $montoRestante -= $monto;

            Abono::create([
                'paciente_id' => $paciente->id,
                'presupuesto_id' => $presupuesto->id,
                'registrado_por_id' => $dentista->id,
                'monto' => $monto,
                'metodo_pago' => fake()->randomElement(['efectivo', 'efectivo', 'tarjeta', 'transferencia']),
                'fecha_pago' => $presupuesto->fecha_emision->addDays($i * 7 + random_int(1, 5)),
                'referencia' => fake()->optional(0.3)->bothify('REF-####-????'),
                'estado' => 'completado',
                'fecha_abono' => $presupuesto->fecha_emision->addDays($i * 7 + random_int(1, 5)),
            ]);
        }
    }

    private function crearRecetas(iterable $citasFinalizadas): void
    {
        foreach ($citasFinalizadas as $cita) {
            if (!fake()->boolean(60)) {
                continue;
            }

            $receta = Receta::create(['cita_id' => $cita->id]);

            $numMedicamentos = random_int(1, 3);
            $medicamentosUsados = fake()->randomElements(self::MEDICAMENTOS_RECETA, $numMedicamentos);

            foreach ($medicamentosUsados as $med) {
                RecetaDetalle::create([
                    'receta_id' => $receta->id,
                    'medicamento' => $med['medicamento'],
                    'dosis' => $med['dosis'],
                    'frecuencia' => $med['frecuencia'],
                    'duracion' => $med['duracion'],
                ]);
            }
        }
    }

    private function getOrCreateUsers(UserRolEnum $rol, int $count): \Illuminate\Support\Collection
    {
        $users = User::where('rol', $rol)->get();

        if ($users->count() < $count) {
            $remaining = $count - $users->count();
            $newUsers = User::factory($remaining)->create(['rol' => $rol]);
            $users = $users->concat($newUsers);
        }

        return $users;
    }
}
