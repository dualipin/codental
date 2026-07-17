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
        Schema::create('abono_distribucions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movimiento_caja_id')->constrained('movimiento_cajas')->cascadeOnDelete();
            $table->foreignId('presupuesto_detalle_id')->constrained('presupuesto_detalles')->cascadeOnDelete();
            $table->decimal('monto_aplicado', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abono_distribucions');
    }
};
