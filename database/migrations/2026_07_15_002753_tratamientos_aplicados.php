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
        Schema::create('tratamientos_aplicados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hallazgo_dental_id')->nullable()->constrained('hallazgos_dentales');
            // nullable porque hay tratamientos preventivos (limpieza) sin
            // un diagnóstico previo asociado
            $table->foreignId('paciente_id')->constrained('pacientes');
            $table->foreignId('diente_id')->constrained('dientes');
            $table->foreignId('cara_dental_id')->nullable()->constrained('caras_dentales');
            $table->foreignId('tratamiento_id')->constrained('tratamientos');
            $table->foreignId('precio_tratamiento_id')->nullable()->constrained('precios_tratamientos');
            // referencia al precio vigente que se cobró, para no perder el
            // valor histórico aunque el precio cambie después
            $table->enum('estado', ['planificado', 'en_proceso', 'completado', 'cancelado'])->default('planificado');
            $table->date('fecha_planificada')->nullable();
            $table->date('fecha_realizado')->nullable();
            $table->foreignId('odontologo_id')->nullable()->constrained('users');
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->index(['paciente_id', 'diente_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tratamientos_aplicados');
    }
};
