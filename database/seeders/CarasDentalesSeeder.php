<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarasDentalesSeeder extends Seeder
{
    private array $caras_dentales = [
        ['nombre' => 'Mesial', 'descripcion' => 'Cara en contacto con el diente hacia la línea media'],
        ['nombre' => 'Distal', 'descripcion' => 'Cara en contacto con el diente alejado de la línea media'],
        ['nombre' => 'Oclusal', 'descripcion' => 'Cara masticatoria (molares y premolares)'],
        ['nombre' => 'Incisal', 'descripcion' => 'Borde cortante (incisivos y caninos)'],
        ['nombre' => 'Vestibular', 'descripcion' => 'Cara hacia la mejilla/labio'],
        ['nombre' => 'Lingual', 'descripcion' => 'Cara hacia la lengua (dientes inferiores)'],
        ['nombre' => 'Palatino', 'descripcion' => 'Cara hacia el paladar (dientes superiores)'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('caras_dentales')->insert($this->caras_dentales);
    }
}
