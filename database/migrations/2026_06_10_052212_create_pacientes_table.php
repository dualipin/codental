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
        Schema::create('pacientes', function (Blueprint $table) {
            $table->string('idp', 40)->primary()->comment('idpaciente');
        
        // Datos del Paciente
        $table->string('pnom', 40)->comment('paciente_nombre');
        $table->string('papp', 40)->comment('paciente_apellido paterno');
        $table->string('papm', 40)->comment('paciente_apellido materno');
        $table->date('pnac')->comment('paciente_nacimiento');
        $table->string('psex', 1)->comment('paciente_sexo');
        $table->string('pdir', 100)->comment('paciente_direccion');
        $table->string('pest', 20)->comment('paciente_estado');
        $table->string('pmun', 25)->comment('paciente_municipio');
        $table->string('ptel', 10)->unique()->comment('paciente_telefono');
        $table ->string('pocu', 10)->comment('paciente_ocupacion');
        $table->string('pciv', 15)->comment('paciente_estado_civil');
        $table->string('pcor', 35)->unique()->comment('paciente_correo');
        $table->string('prel', 30)->nullable()->comment('paciente_religion');
        $table->string('penv', 40)->nullable()->comment('enviado_por');
        $table->string('pmot', 100)->nullable()->comment('motivo_consulta');        
        $table->string('d_user', 40)->comment('doctor_user');
        $table->string('preal', 10)->default('pendiente')->comment('paciente_real');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
