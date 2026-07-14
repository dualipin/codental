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
        Schema::create('precios_trat', function (Blueprint $table) {
             $table->id('id_precio')->comment("ID_precio");         // ID precio
            $table->foreignId('id_trat')->comment("ID_tratamiento")->constrained('cat_tratamientos', 'id_trat')->onDelete('cascade'); // ID tratamiento
            $table->string('tip_die', 20)->comment("Tipo diente");    // tipo_diente
            $table->decimal('prec', 10, 2)->comment("Precio");   // precio
            $table->decimal('prec_ant', 10, 2)->comment("Precio anterior")->nullable(); // precio_anterior
            $table->date('vig_des')->comment("Vigencia desde");          // vigencia_desde
            $table->date('vig_has')->comment("Vigencia hasta")->nullable(); // vigencia_hasta
            $table->timestamps();
            
            $table->index(['id_trat', 'tip_die']);
        });

        $tratamientos = DB::table('cat_tratamientos')->pluck('id_trat', 'nom')->toArray();

        $precios = [
            // Obturación de Caries
            [
                'id_trat' => $tratamientos['Obturación de Caries'],
                'tip_die' => 'Incisivo',
                'prec' => 800.00,
                'vig_des' => '2024-01-01'
            ],
            [
                'id_trat' => $tratamientos['Obturación de Caries'],
                'tip_die' => 'Canino',
                'prec' => 900.00,
                'vig_des' => '2024-01-01'
            ],
            [
                'id_trat' => $tratamientos['Obturación de Caries'],
                'tip_die' => 'Premolar',
                'prec' => 1000.00,
                'vig_des' => '2024-01-01'
            ],
            [
                'id_trat' => $tratamientos['Obturación de Caries'],
                'tip_die' => 'Molar',
                'prec' => 1200.00,
                'vig_des' => '2024-01-01'
            ],
            
            // Endodoncia
            [
                'id_trat' => $tratamientos['Endodoncia'],
                'tip_die' => 'Incisivo',
                'prec' => 1500.00,
                'vig_des' => '2024-01-01'
            ],
            [
                'id_trat' => $tratamientos['Endodoncia'],
                'tip_die' => 'Canino',
                'prec' => 1700.00,
                'vig_des' => '2024-01-01'
            ],
            [
                'id_trat' => $tratamientos['Endodoncia'],
                'tip_die' => 'Premolar',
                'prec' => 2000.00,
                'vig_des' => '2024-01-01'
            ],
            [
                'id_trat' => $tratamientos['Endodoncia'],
                'tip_die' => 'Molar',
                'prec' => 2500.00,
                'vig_des' => '2024-01-01'
            ],
            
            // Corona Dental
            [
                'id_trat' => $tratamientos['Corona Dental'],
                'tip_die' => 'Incisivo',
                'prec' => 2000.00,
                'vig_des' => '2024-01-01'
            ],
            [
                'id_trat' => $tratamientos['Corona Dental'],
                'tip_die' => 'Canino',
                'prec' => 2200.00,
                'vig_des' => '2024-01-01'
            ],
            [
                'id_trat' => $tratamientos['Corona Dental'],
                'tip_die' => 'Premolar',
                'prec' => 2500.00,
                'vig_des' => '2024-01-01'
            ],
            [
                'id_trat' => $tratamientos['Corona Dental'],
                'tip_die' => 'Molar',
                'prec' => 3000.00,
                'vig_des' => '2024-01-01'
            ],
            
            // Limpieza Dental (mismo precio para todos)
            [
                'id_trat' => $tratamientos['Limpieza Dental'],
                'tip_die' => 'Incisivo',
                'prec' => 600.00,
                'vig_des' => '2024-01-01'
            ],
            [
                'id_trat' => $tratamientos['Limpieza Dental'],
                'tip_die' => 'Canino',
                'prec' => 600.00,
                'vig_des' => '2024-01-01'
            ],
            [
                'id_trat' => $tratamientos['Limpieza Dental'],
                'tip_die' => 'Premolar',
                'prec' => 600.00,
                'vig_des' => '2024-01-01'
            ],
            [
                'id_trat' => $tratamientos['Limpieza Dental'],
                'tip_die' => 'Molar',
                'prec' => 600.00,
                'vig_des' => '2024-01-01'
            ],
            
            // Extracción Dental
            [
                'id_trat' => $tratamientos['Extracción Dental'],
                'tip_die' => 'Incisivo',
                'prec' => 800.00,
                'vig_des' => '2024-01-01'
            ],
            [
                'id_trat' => $tratamientos['Extracción Dental'],
                'tip_die' => 'Canino',
                'prec' => 1000.00,
                'vig_des' => '2024-01-01'
            ],
            [
                'id_trat' => $tratamientos['Extracción Dental'],
                'tip_die' => 'Premolar',
                'prec' => 1200.00,
                'vig_des' => '2024-01-01'
            ],
            [
                'id_trat' => $tratamientos['Extracción Dental'],
                'tip_die' => 'Molar',
                'prec' => 1500.00,
                'vig_des' => '2024-01-01'
            ],
            
            // Blanqueamiento Dental
            [
                'id_trat' => $tratamientos['Blanqueamiento Dental'],
                'tip_die' => 'Incisivo',
                'prec' => 3000.00,
                'vig_des' => '2024-01-01'
            ],
            [
                'id_trat' => $tratamientos['Blanqueamiento Dental'],
                'tip_die' => 'Canino',
                'prec' => 3000.00,
                'vig_des' => '2024-01-01'
            ],
            [
                'id_trat' => $tratamientos['Blanqueamiento Dental'],
                'tip_die' => 'Premolar',
                'prec' => 3000.00,
                'vig_des' => '2024-01-01'
            ],
            [
                'id_trat' => $tratamientos['Blanqueamiento Dental'],
                'tip_die' => 'Molar',
                'prec' => 3000.00,
                'vig_des' => '2024-01-01'
            ],
        ];

        DB::table('precios_trat')->insert($precios);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('precios_trat');
    }
};
