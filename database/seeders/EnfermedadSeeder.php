<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnfermedadSeeder extends Seeder
{

    private array $categoria_enfermedades = [
        [
            'nombre' => 'Caries',
            'descripcion' => 'Caries dental en esmalte o dentina',
            'gravedad' => 'Moderada',
            'color' => '#FF6B6B'
        ],
        [
            'nombre' => 'Fractura',
            'descripcion' => 'Fractura dental parcial o total',
            'gravedad' => 'Severa',
            'color' => '#FF9F43'
        ],
        [
            'nombre' => 'Mancha',
            'descripcion' => 'Mancha por fluorosis o hipoplasia',
            'gravedad' => 'Leve',
            'color' => '#FECA57'
        ],
        [
            'nombre' => 'Gingivitis',
            'descripcion' => 'Inflamación de encías',
            'gravedad' => 'Moderada',
            'color' => '#48DBFB'
        ],
        [
            'nombre' => 'Periodontitis',
            'descripcion' => 'Enfermedad periodontal avanzada',
            'gravedad' => 'Severa',
            'color' => '#FF4757'
        ],
        [
            'nombre' => 'Absceso',
            'descripcion' => 'Infección dental con pus',
            'gravedad' => 'Severa',
            'color' => '#FF6348'
        ],
        [
            'nombre' => 'Desgaste',
            'descripcion' => 'Desgaste dental por bruxismo o erosión',
            'gravedad' => 'Leve',
            'color' => '#A29BFE'
        ],
        [
            'nombre' => 'Sensibilidad',
            'descripcion' => 'Sensibilidad dental al frío/calor',
            'gravedad' => 'Leve',
            'color' => '#FD79A8'
        ],
        [
            'nombre' => 'Mala Oclusión',
            'descripcion' => 'Mal alineamiento dental',
            'gravedad' => 'Moderada',
            'color' => '#00B894'
        ],
        [
            'nombre' => 'Halitosis',
            'descripcion' => 'Mal aliento crónico',
            'gravedad' => 'Leve',
            'color' => '#00CEC9'
        ],
        [
            'nombre' => 'Bruxismo',
            'descripcion' => 'Rechinamiento dental nocturno',
            'gravedad' => 'Moderada',
            'color' => '#6C5CE7'
        ],
        [
            'nombre' => 'Lesión Pulpar',
            'descripcion' => 'Daño en la pulpa dental',
            'gravedad' => 'Severa',
            'color' => '#E17055'
        ],
        [
            'nombre' => 'Quiste Dental',
            'descripcion' => 'Quiste en la raíz dental',
            'gravedad' => 'Severa',
            'color' => '#D63031'
        ],
        [
            'nombre' => 'Anomalía Dental',
            'descripcion' => 'Anomalía en forma o tamaño dental',
            'gravedad' => 'Moderada',
            'color' => '#0984E3'
        ],
        [
            'nombre' => 'Fístula Dental',
            'descripcion' => 'Fístula en la encía por infección',
            'gravedad' => 'Severa',
            'color' => '#E84393'
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timestamp = now();
        $data = array_map(fn (array $item) => [
            ...$item,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ], $this->categoria_enfermedades);

        DB::table('categoria_enfermedades')->insert($data);
    }
}
