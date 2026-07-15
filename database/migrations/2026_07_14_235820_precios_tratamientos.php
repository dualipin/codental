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
        Schema::create('precios_tratamientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tratamiento_id')->constrained('tratamientos')->cascadeOnDelete();
            $table->string('tipo_diente', 20)->nullable();
            $table->decimal('precio', 10, 2);
            $table->decimal('precio_anterior', 10, 2);
            $table->date('vigente_desde');
            $table->date('vigente_hasta')->nullable();
            $table->timestamps();
            $table->index(['tratamiento_id', 'tipo_diente']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('precios_tratamientos');
    }
};
