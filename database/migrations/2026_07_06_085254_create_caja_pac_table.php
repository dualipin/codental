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
        Schema::create('caja_pac', function (Blueprint $table) {
            $table->id('id_caja');           // ID caja
            $table->string('id_pac', 40)->unique(); // ID paciente
            $table->foreign('id_pac')->references('idp')->on('pacientes')->cascadeOnDelete();
            
            // Campos de descuento (NUEVOS)
            $table->decimal('des_tot', 12, 2)->default(0.00)->comment('Descuento total aplicado'); // descuento total
            $table->decimal('mon_net', 12, 2)->default(0.00)->comment('Monto neto (total - descuentos)'); // monto neto
            
            $table->decimal('mon_tot', 12, 2)->default(0); // monto_total_contratado
            $table->decimal('mon_abo', 12, 2)->default(0); // monto_abonado
            $table->decimal('sal_pen', 12, 2)->default(0); // saldo_pendiente
            $table->date('fec_ult_abo')->nullable(); // fecha_ultimo_abono
            $table->enum('est_cue', ['Al día', 'Adeudo', 'Pagado en total'])->default('Al día'); // estado_cuenta
            $table->timestamps();
            
            $table->index(['sal_pen', 'est_cue']);
            $table->index('est_cue');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja_pac');
    }
};
