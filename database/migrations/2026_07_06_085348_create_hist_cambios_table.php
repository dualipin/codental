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
        Schema::create('hist_cambios', function (Blueprint $table) {
            $table->id('id_hist');           // ID historial
            $table->foreignId('id_odot')->constrained('odonto_trat', 'id_odot')->onDelete('cascade'); // ID odontograma tratamiento
            $table->foreignId('id_det')->constrained('dientes_est_trat', 'id_det')->onDelete('cascade'); // ID diente estado tratamiento
            $table->enum('acc', ['Agregar_enfermedad', 'Eliminar_enfermedad', 'Cambiar_estado', 'Aplicar_descuento']); // accion (NUEVO)
            $table->timestamp('fec_cam')->useCurrent(); // fecha_cambio
            $table->string('usu', 50);        // usuario
            
            // Campos de descuento (NUEVOS)
            $table->json('dat_ant')->nullable(); // datos_previos
            $table->json('dat_nue')->nullable(); // datos_nuevos
            $table->timestamps();
            
            $table->index(['id_odot', 'fec_cam']);
            $table->index('fec_cam');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hist_cambios');
    }
};
