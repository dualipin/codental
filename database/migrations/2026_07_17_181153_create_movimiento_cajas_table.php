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
        Schema::create('movimiento_cajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes');
            $table->enum('tipo_movimiento', ['ingreso', 'anulacion']);
            $table->decimal('monto', 10, 2);
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia', 'mixto']);
            $table->string('referencia_bancaria')->nullable();
            $table->foreignId('usuario_id')->constrained('users');
            $table->timestamp('fecha_hora')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento_cajas');
    }
};
