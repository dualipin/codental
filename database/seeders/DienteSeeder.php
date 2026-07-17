<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DienteSeeder extends Seeder
{
    use WithoutModelEvents;

    private array $dientesAdultos = [
        // Cuadrante 1 (superior derecho) - FDI 11-18
        ['numero_fdi' => 18, 'nombre' => 'Tercer molar', 'cuadrante' => '1', 'tipo' => 'permanente', 'posicion' => 'posterior'],
        ['numero_fdi' => 17, 'nombre' => 'Segundo molar', 'cuadrante' => '1', 'tipo' => 'permanente', 'posicion' => 'posterior'],
        ['numero_fdi' => 16, 'nombre' => 'Primer molar', 'cuadrante' => '1', 'tipo' => 'permanente', 'posicion' => 'posterior'],
        ['numero_fdi' => 15, 'nombre' => 'Segundo premolar', 'cuadrante' => '1', 'tipo' => 'permanente', 'posicion' => 'posterior'],
        ['numero_fdi' => 14, 'nombre' => 'Primer premolar', 'cuadrante' => '1', 'tipo' => 'permanente', 'posicion' => 'posterior'],
        ['numero_fdi' => 13, 'nombre' => 'Canino', 'cuadrante' => '1', 'tipo' => 'permanente', 'posicion' => 'anterior'],
        ['numero_fdi' => 12, 'nombre' => 'Incisivo lateral', 'cuadrante' => '1', 'tipo' => 'permanente', 'posicion' => 'anterior'],
        ['numero_fdi' => 11, 'nombre' => 'Incisivo central', 'cuadrante' => '1', 'tipo' => 'permanente', 'posicion' => 'anterior'],

        // Cuadrante 2 (superior izquierdo) - FDI 21-28
        ['numero_fdi' => 21, 'nombre' => 'Incisivo central', 'cuadrante' => '2', 'tipo' => 'permanente', 'posicion' => 'anterior'],
        ['numero_fdi' => 22, 'nombre' => 'Incisivo lateral', 'cuadrante' => '2', 'tipo' => 'permanente', 'posicion' => 'anterior'],
        ['numero_fdi' => 23, 'nombre' => 'Canino', 'cuadrante' => '2', 'tipo' => 'permanente', 'posicion' => 'anterior'],
        ['numero_fdi' => 24, 'nombre' => 'Primer premolar', 'cuadrante' => '2', 'tipo' => 'permanente', 'posicion' => 'posterior'],
        ['numero_fdi' => 25, 'nombre' => 'Segundo premolar', 'cuadrante' => '2', 'tipo' => 'permanente', 'posicion' => 'posterior'],
        ['numero_fdi' => 26, 'nombre' => 'Primer molar', 'cuadrante' => '2', 'tipo' => 'permanente', 'posicion' => 'posterior'],
        ['numero_fdi' => 27, 'nombre' => 'Segundo molar', 'cuadrante' => '2', 'tipo' => 'permanente', 'posicion' => 'posterior'],
        ['numero_fdi' => 28, 'nombre' => 'Tercer molar', 'cuadrante' => '2', 'tipo' => 'permanente', 'posicion' => 'posterior'],

        // Cuadrante 3 (inferior izquierdo) - FDI 31-38
        ['numero_fdi' => 38, 'nombre' => 'Tercer molar', 'cuadrante' => '3', 'tipo' => 'permanente', 'posicion' => 'posterior'],
        ['numero_fdi' => 37, 'nombre' => 'Segundo molar', 'cuadrante' => '3', 'tipo' => 'permanente', 'posicion' => 'posterior'],
        ['numero_fdi' => 36, 'nombre' => 'Primer molar', 'cuadrante' => '3', 'tipo' => 'permanente', 'posicion' => 'posterior'],
        ['numero_fdi' => 35, 'nombre' => 'Segundo premolar', 'cuadrante' => '3', 'tipo' => 'permanente', 'posicion' => 'posterior'],
        ['numero_fdi' => 34, 'nombre' => 'Primer premolar', 'cuadrante' => '3', 'tipo' => 'permanente', 'posicion' => 'posterior'],
        ['numero_fdi' => 33, 'nombre' => 'Canino', 'cuadrante' => '3', 'tipo' => 'permanente', 'posicion' => 'anterior'],
        ['numero_fdi' => 32, 'nombre' => 'Incisivo lateral', 'cuadrante' => '3', 'tipo' => 'permanente', 'posicion' => 'anterior'],
        ['numero_fdi' => 31, 'nombre' => 'Incisivo central', 'cuadrante' => '3', 'tipo' => 'permanente', 'posicion' => 'anterior'],

        // Cuadrante 4 (inferior derecho) - FDI 41-48
        ['numero_fdi' => 41, 'nombre' => 'Incisivo central', 'cuadrante' => '4', 'tipo' => 'permanente', 'posicion' => 'anterior'],
        ['numero_fdi' => 42, 'nombre' => 'Incisivo lateral', 'cuadrante' => '4', 'tipo' => 'permanente', 'posicion' => 'anterior'],
        ['numero_fdi' => 43, 'nombre' => 'Canino', 'cuadrante' => '4', 'tipo' => 'permanente', 'posicion' => 'anterior'],
        ['numero_fdi' => 44, 'nombre' => 'Primer premolar', 'cuadrante' => '4', 'tipo' => 'permanente', 'posicion' => 'posterior'],
        ['numero_fdi' => 45, 'nombre' => 'Segundo premolar', 'cuadrante' => '4', 'tipo' => 'permanente', 'posicion' => 'posterior'],
        ['numero_fdi' => 46, 'nombre' => 'Primer molar', 'cuadrante' => '4', 'tipo' => 'permanente', 'posicion' => 'posterior'],
        ['numero_fdi' => 47, 'nombre' => 'Segundo molar', 'cuadrante' => '4', 'tipo' => 'permanente', 'posicion' => 'posterior'],
        ['numero_fdi' => 48, 'nombre' => 'Tercer molar', 'cuadrante' => '4', 'tipo' => 'permanente', 'posicion' => 'posterior'],
    ];

    public function run(): void
    {
        $timestamp = now();
        $data = array_map(fn (array $item) => [
            ...$item,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ], $this->dientesAdultos);

        DB::table('dientes')->insert($data);
    }
}
