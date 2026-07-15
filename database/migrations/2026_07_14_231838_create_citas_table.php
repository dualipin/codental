<?php

use App\Enums\EstatusCitaEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();

            // Datos del Paciente
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('dentista_id')->constrained('users')->cascadeOnDelete();

            $table->datetime('fecha_inicio');
            $table->datetime('fecha_fin');

            $table->string('estatus', 20)->default(EstatusCitaEnum::PENDIENTE->value);
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
