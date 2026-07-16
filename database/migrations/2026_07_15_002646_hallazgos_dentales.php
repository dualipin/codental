<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hallazgos_dentales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('odontograma_id')->constrained('odontogramas')->onDelete('cascade');
            $table->foreignId('diente_id')->constrained('dientes');
            $table->foreignId('cara_dental_id')->nullable()->constrained('caras_dentales');
            // nullable porque hallazgos como "diente ausente" o "diente extraído"
            // afectan la pieza completa, no una cara específica
            $table->foreignId('enfermedad_id')->constrained('enfermedades');
            $table->enum('estado', ['activo', 'resuelto', 'descartado'])->default('activo');
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->index(['odontograma_id', 'diente_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hallazgos_dentales');
    }
};
