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
        Schema::create('consultas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('odontologo_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('cita_id')->nullable()->constrained('citas')->nullOnDelete();
            $table->date('fecha_consulta');

            // Habitus exterior / Signos vitales
            $table->text('motivo_consulta')->nullable();
            $table->decimal('peso', 8, 2)->nullable();
            $table->decimal('estatura', 8, 2)->nullable();
            $table->decimal('temperatura', 5, 2)->nullable();
            $table->decimal('frecuencia_cardiaca', 5, 2)->nullable();
            $table->decimal('presion_arterial', 7, 2)->nullable();

            $table->text('nota_evolucion')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultas');
    }
};
