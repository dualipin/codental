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
        Schema::create('presupuesto_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presupuesto_id')->constrained('presupuestos')->cascadeOnDelete();
            $table->foreignId('tratamiento_catalogo_id')->constrained('tratamiento_catalogos');
            $table->string('referencia_odontograma_id')->nullable();
            $table->decimal('precio_congelado', 10, 2);
            $table->decimal('monto_descuento', 10, 2)->default(0);
            $table->string('justificacion_descuento')->nullable();
            $table->enum('estado_tratamiento', ['pendiente', 'en_progreso', 'completado'])->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presupuesto_detalles');
    }
};
