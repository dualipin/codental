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
        Schema::create('citas', function (Blueprint $table) {
        
        $table->string('idc', 40)->primary()->comment('idcita');
        
        // Datos del Paciente
        $table->string('idp', 40)->comment('idpaciente');
        // NUEVO CONTROL: Fechas y Horas de Inicio y Fin combinadas
        $table->datetime('fec_i')->comment('fecha_hora_inicio');
        $table->datetime('fec_f')->comment('fecha_hora_final');

        
        $table->string('d_user', 40)->comment('doctor_user'); 
        $table->string('est', 15)->default('pendiente'); 
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
