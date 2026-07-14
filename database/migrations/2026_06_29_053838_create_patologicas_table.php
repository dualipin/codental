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
        Schema::create('patologicas', function (Blueprint $table) {
            $table->string('idp', 40)->comment('idpaciente');
            $table->unsignedInteger('idae')->comment('id_enfermedad');
            $table->string('esp', 35)->nullable()->default(null)->comment('especifique enfermedad');
            $table->timestamps();

            // Evita que un paciente tenga repetida la misma enfermedad.
            $table->primary(['idp', 'idae']);

            $table->foreign('idp')
                ->references('idp')
                ->on('pacientes')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('idae')
                ->references('idae')
                ->on('enfermedades')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patologicas');
    }
};
