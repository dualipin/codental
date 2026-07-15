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
        Schema::create('odontograma_dientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('odontograma_id')->constrained('odontogramas')->onDelete('cascade');
            $table->foreignId('diente_id')->constrained('dientes');
            $table->enum('estado_general', [
                'sano',
                'con_caries',
                'obturado',
                'con_corona',
                'con_implante',
                'extraido',
                'ausente_congenito'
            ])->default('sano');
            $table->timestamps();

            $table->unique(['odontograma_id', 'diente_id']);
            // un diente solo puede tener UN estado general por odontograma
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('odontograma_dientes');
    }
};
