<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cat_enfermedades', function (Blueprint $table) {
            $table->id('id_enf');            // ID enfermedad
            $table->string('nom', 50);        // nombre
            $table->string('desc', 150)->nullable(); // descripcion
            $table->string('cod', 10)->unique()->nullable(); // codigo
            $table->enum('grav', ['Leve', 'Moderada', 'Severa'])->default('Leve'); // gravedad
            
            // NUEVO: Campo para color
            $table->string('color', 7)->default('#FF6B6B')->comment('Color en formato hexadecimal (#RRGGBB)');
            
            $table->timestamps();

        });
        $enfermedades = [
            [
                'nom' => 'Caries',
                'desc' => 'Caries dental en esmalte o dentina',
                'cod' => 'K02.9',
                'grav' => 'Moderada',
                'color' => '#FF6B6B',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Fractura',
                'desc' => 'Fractura dental parcial o total',
                'cod' => 'S02.5',
                'grav' => 'Severa',
                'color' => '#FF9F43',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Mancha',
                'desc' => 'Mancha por fluorosis o hipoplasia',
                'cod' => 'K00.3',
                'grav' => 'Leve',
                'color' => '#FECA57',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Gingivitis',
                'desc' => 'Inflamación de encías',
                'cod' => 'K05.0',
                'grav' => 'Moderada',
                'color' => '#48DBFB',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Periodontitis',
                'desc' => 'Enfermedad periodontal avanzada',
                'cod' => 'K05.3',
                'grav' => 'Severa',
                'color' => '#FF4757',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Absceso',
                'desc' => 'Infección dental con pus',
                'cod' => 'K04.7',
                'grav' => 'Severa',
                'color' => '#FF6348',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Desgaste',
                'desc' => 'Desgaste dental por bruxismo o erosión',
                'cod' => 'K03.0',
                'grav' => 'Leve',
                'color' => '#A29BFE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Sensibilidad',
                'desc' => 'Sensibilidad dental al frío/calor',
                'cod' => 'K03.8',
                'grav' => 'Leve',
                'color' => '#FD79A8',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Mala Oclusión',
                'desc' => 'Mal alineamiento dental',
                'cod' => 'K07.4',
                'grav' => 'Moderada',
                'color' => '#00B894',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Halitosis',
                'desc' => 'Mal aliento crónico',
                'cod' => 'R19.6',
                'grav' => 'Leve',
                'color' => '#00CEC9',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Bruxismo',
                'desc' => 'Rechinamiento dental nocturno',
                'cod' => 'F45.8',
                'grav' => 'Moderada',
                'color' => '#6C5CE7',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Lesión Pulpar',
                'desc' => 'Daño en la pulpa dental',
                'cod' => 'K04.0',
                'grav' => 'Severa',
                'color' => '#E17055',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Quiste Dental',
                'desc' => 'Quiste en la raíz dental',
                'cod' => 'K04.8',
                'grav' => 'Severa',
                'color' => '#D63031',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Anomalía Dental',
                'desc' => 'Anomalía en forma o tamaño dental',
                'cod' => 'K00.2',
                'grav' => 'Moderada',
                'color' => '#0984E3',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Fístula Dental',
                'desc' => 'Fístula en la encía por infección',
                'cod' => 'K04.6',
                'grav' => 'Severa',
                'color' => '#E84393',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('cat_enfermedades')->insert($enfermedades);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cat_enfermedades');
    }
};
