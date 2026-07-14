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
        Schema::create('dientes_est_ini', function (Blueprint $table) {
            $table->id('id_dei')->comment("ID diente estado inicial");            // ID diente estado inicial
            $table->foreignId('id_odoi')->comment("ID odontograma inicial")->constrained('odonto_inicial', 'id_odoi')->onDelete('cascade'); // ID odontograma inicial
            $table->foreignId('id_die')->comment("ID diente")->constrained('cat_dientes', 'id_diente')->onDelete('cascade'); // ID diente
            $table->foreignId('id_car')->comment("ID cara")->constrained('cat_caras', 'id_cara')->onDelete('cascade'); // ID cara
            $table->timestamps();
            
            $table->unique(['id_odoi', 'id_die', 'id_car'], 'uniq_die_car_ini');
            $table->index('id_die');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dientes_est_ini');
    }
};
