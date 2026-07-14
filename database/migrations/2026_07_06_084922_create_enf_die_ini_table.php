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
        Schema::create('enf_die_ini', function (Blueprint $table) {
            $table->id('id_edi');            // ID enfermedad diente inicial
            $table->foreignId('id_dei')->constrained('dientes_est_ini', 'id_dei')->onDelete('cascade'); // ID diente estado inicial
            $table->foreignId('id_enf')->constrained('cat_enfermedades', 'id_enf')->onDelete('cascade'); // ID enfermedad
            $table->string('obs', 100)->nullable(); // observaciones
            $table->timestamps();
            
            $table->unique(['id_dei', 'id_enf'], 'uniq_enf_die_ini');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enf_die_ini');
    }
};
