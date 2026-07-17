<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarasDentalesSeeder extends Seeder
{
    use WithoutModelEvents;

    private array $carasDentales = [
        ['nombre' => 'Vestibular', 'codigo' => 'V', 'descripcion' => 'Cara hacia la mejilla/labio'],
        ['nombre' => 'Lingual', 'codigo' => 'L', 'descripcion' => 'Cara hacia la lengua (dientes inferiores)'],
        ['nombre' => 'Palatino', 'codigo' => 'L', 'descripcion' => 'Cara hacia el paladar (dientes superiores)'],
        ['nombre' => 'Mesial', 'codigo' => 'M', 'descripcion' => 'Cara en contacto con el diente hacia la línea media'],
        ['nombre' => 'Distal', 'codigo' => 'D', 'descripcion' => 'Cara en contacto con el diente alejado de la línea media'],
        ['nombre' => 'Oclusal', 'codigo' => 'O', 'descripcion' => 'Cara masticatoria (molares y premolares)'],
        ['nombre' => 'Incisal', 'codigo' => 'O', 'descripcion' => 'Borde cortante (incisivos y caninos)'],
    ];

    public function run(): void
    {
        $timestamp = now();
        $data = array_map(fn (array $item) => [
            ...$item,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ], $this->carasDentales);

        DB::table('caras_dentales')->insert($data);
    }
}
