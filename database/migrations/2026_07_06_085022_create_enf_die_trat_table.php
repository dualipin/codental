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
        Schema::create('enf_die_trat', function (Blueprint $table) {
            $table->id('id_edt');            // ID enfermedad diente tratamiento
            $table->foreignId('id_det')->constrained('dientes_est_trat', 'id_det')->onDelete('cascade'); // ID diente estado tratamiento
            $table->foreignId('id_enf')->constrained('cat_enfermedades', 'id_enf')->onDelete('cascade'); // ID enfermedad
            $table->foreignId('id_edi')->nullable()->constrained('enf_die_ini', 'id_edi')->onDelete('set null'); // ID enfermedad inicial
            $table->date('fec_dia');          // fecha_diagnostico
            $table->date('fec_eli')->nullable(); // fecha_eliminacion
            $table->enum('est', ['Activa', 'Tratada', 'Eliminada'])->default('Activa'); // estado
            $table->string('obs', 100)->nullable(); // observaciones
            $table->timestamps();
            
            $table->index(['id_enf', 'est']);
            $table->index('est');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enf_die_trat');
    }
};
