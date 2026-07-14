<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dientes_est_trat', function (Blueprint $table) {
            $table->id('id_det')->comment("ID diente estado tratamiento");            // ID diente estado tratamiento
            $table->foreignId('id_odot')->comment("ID odontograma tratamiento")->constrained('odonto_trat', 'id_odot')->onDelete('cascade'); // ID odontograma tratamiento
            $table->foreignId('id_die')->comment("ID diente")->constrained('cat_dientes', 'id_diente')->onDelete('cascade'); // ID diente
            $table->foreignId('id_car')->comment("ID cara")->constrained('cat_caras', 'id_cara')->onDelete('cascade'); // ID cara
            $table->enum('est', ['Pendiente', 'En tratamiento', 'Completado'])->default('Pendiente'); // estado
            $table->date('fec_ini')->nullable(); // fecha_inicio
            $table->date('fec_com')->nullable(); // fecha_completado
            $table->timestamps();
            
            $table->unique(['id_odot', 'id_die', 'id_car'], 'uniq_die_car_trat');
            $table->index(['id_die', 'est']);
            $table->index('est');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dientes_est_trat');
    }
};
