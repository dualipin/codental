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
        Schema::create('abonos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('presupuesto_id')->nullable()->constrained('presupuestos')->nullOnDelete();
            $table->foreignId('registrado_por_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('monto', 10, 2);
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia'])->default('efectivo');
            $table->date('fecha_pago');
            $table->string('referencia')->nullable();
            $table->enum('estado', ['pendiente', 'completado', 'cancelado'])->default('pendiente');
            $table->timestamp('fecha_abono');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abonos');
    }
};
