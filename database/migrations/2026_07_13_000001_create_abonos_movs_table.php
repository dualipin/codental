<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abonos_movs', function (Blueprint $table) {
            $table->id('id_abo');
            $table->string('id_pac', 40);
            $table->foreign('id_pac')->references('idp')->on('pacientes')->cascadeOnDelete();
            $table->foreignId('id_pts')->nullable()->constrained('pac_trat_sel', 'id_pts')->nullOnDelete();
            $table->decimal('mon', 12, 2)->default(0);
            $table->date('fec_abo');
            $table->string('met_pag', 30);
            $table->string('ref', 80)->nullable();
            $table->string('obs', 250)->nullable();
            $table->decimal('des_apl', 12, 2)->default(0);
            $table->enum('est', ['Activo', 'Anulado'])->default('Activo');
            $table->string('id_usuario_registro', 40)->nullable();
            $table->string('mot_anulacion', 250)->nullable();
            $table->timestamps();

            $table->index(['id_pac', 'fec_abo']);
            $table->index(['est', 'fec_abo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abonos_movs');
    }
};
