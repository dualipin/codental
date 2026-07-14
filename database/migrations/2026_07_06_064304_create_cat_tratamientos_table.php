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
        Schema::create('cat_tratamientos', function (Blueprint $table) {
            $table->id('id_trat')->comment('ID tratamiento');           // ID tratamiento
            $table->string('nom', 50)->comment('nombre');        // nombre
            $table->string('desc', 150)->nullable()->comment('descripcion'); // descripcion
            $table->string('cod', 10)->unique()->nullable()->comment('codigo'); // codigo
            $table->timestamps();
        });

        $tratamientos = [
            [
                'nom' => 'Obturación de Caries',
                'desc' => 'Eliminación de caries y relleno con amalgama o resina',
                'cod' => 'TRT-001',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Endodoncia',
                'desc' => 'Tratamiento de conducto radicular',
                'cod' => 'TRT-002',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Corona Dental',
                'desc' => 'Colocación de corona protésica',
                'cod' => 'TRT-003',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Limpieza Dental',
                'desc' => 'Profilaxis dental y eliminación de sarro',
                'cod' => 'TRT-004',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Extracción Dental',
                'desc' => 'Extracción quirúrgica del diente',
                'cod' => 'TRT-005',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Blanqueamiento Dental',
                'desc' => 'Blanqueamiento estético dental',
                'cod' => 'TRT-006',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Incrustación Dental',
                'desc' => 'Incrustación de cerámica o composite',
                'cod' => 'TRT-007',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Implante Dental',
                'desc' => 'Colocación de implante dental',
                'cod' => 'TRT-008',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Puente Dental',
                'desc' => 'Colocación de puente dental fijo',
                'cod' => 'TRT-009',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Ortodoncia',
                'desc' => 'Tratamiento de ortodoncia con brackets',
                'cod' => 'TRT-010',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Periodoncia',
                'desc' => 'Tratamiento de enfermedades periodontales',
                'cod' => 'TRT-011',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Sellador Dental',
                'desc' => 'Aplicación de sellador dental preventivo',
                'cod' => 'TRT-012',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Curetaje Dental',
                'desc' => 'Curetaje y alisado radicular',
                'cod' => 'TRT-013',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Prótesis Dental',
                'desc' => 'Prótesis dental removible o fija',
                'cod' => 'TRT-014',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Rehabilitación Oral',
                'desc' => 'Rehabilitación completa de la boca',
                'cod' => 'TRT-015',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('cat_tratamientos')->insert($tratamientos);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cat_tratamientos');
    }
};
