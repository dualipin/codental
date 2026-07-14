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
        Schema::create('antecedentes', function (Blueprint $table) {
// Clave primaria corregida a 'ide'
    $table->integer('ida', true)->comment('Idexpediente antecedentes'); 
    
    $table->string('idp', 40)->nullable()->comment('Idpaciente');
    
    // Datos clínicos generales y fecha
    $table->date('fec')->nullable()->comment('fecha de antecedentes');
    $table->string('hfam', 255)->nullable()->comment('hereditarios familiares');
    $table->string('ale', 50)->nullable()->default(null)->comment('alergias');
    $table->string('meda', 50)->nullable()->default(null)->comment('medicación actual');
    $table->string('nmed', 50)->nullable()->default(null)->comment('nombre de su medico');
    $table->string('mtel', 10)->nullable()->default(null)->comment('telefono del medico');

    $table->string('fre', 15)->nullable()->default(null)->comment('frecuencia de consumo drogas');
    $table->string('san', 3)->nullable()->default(null)->comment('grupo sanguineo');
    
    // Control gineco-obstétrico
    $table->string('emb', 2)->nullable()->default(null)->comment('embarazada');
    $table->string('tie', 10)->nullable()->default(null)->comment('tiempo de gestación');
    $table->string('lat', 2)->nullable()->default(null)->comment('lactancia');
    $table->string('beb', 2)->nullable()->default(null)->comment('meses del bebe');
    
    // Estilo de vida y hábitos
    $table->string('dep', 20)->nullable()->default(null)->comment('deportes');
    $table->string('ali', 10)->nullable()->default(null)->comment('alimentación');
    $table->string('hig', 10)->nullable()->default(null)->comment('higiene');
    
    // Notas médicas y observaciones
    $table->string('cir',2)->nullable()->comment('cirugia?');
    $table->string('cir_des',150)->nullable()->comment('describe_cirugia');
    $table->string('mot',500)->nullable()->comment('motivo de la consulta');

    $table->string('inte',500)->nullable()->comment('interrogatorio');
    $table->string('lab',500)->nullable()->comment('laboratorios');
    
    // Somatometría y Signos Vitales
    $table->string('pes', 4)->nullable()->default(null)->comment('peso');
    $table->string('est', 4)->nullable()->default(null)->comment('estatura');
    $table->string('tem', 4)->nullable()->default(null)->comment('temperatura');
    $table->string('car', 4)->nullable()->default(null)->comment('frecuencia cardiaca');
    $table->string('res', 4)->nullable()->default(null)->comment('frecuencia respiratoria');
    $table->string('pre', 7)->nullable()->default(null)->comment('presion arterial');

    //antecedentes personales bucodentales
    $table->string('ult_rev',100)->nullable()->default(null)->comment('ultima revision');
    $table->string('mot_vis', 50)->nullable()->default(null)->comment('motivo de la visita');
    $table->string('aux_lim', 2)->nullable()->default(null)->comment('auxiliar limitante');
    $table->string('aux_cua', 100)->nullable()->default(null)->comment('auxiliar cuantitativo');
    $table->string('cep_fre', 25)->nullable()->default(null)->comment('cepa frecuente');
    $table->string('ane_loc', 2)->nullable()->default(null)->comment('anestesia local');
    $table->string('ane_com', 2)->nullable()->default(null)->comment('anestesia completa');
    $table->string('ane_des', 50)->nullable()->default(null)->comment('anestesia description');
    $table->string('rem_cas', 2)->nullable()->default(null)->comment('remedio casero o naturista');
    $table->string('rem_des', 30)->nullable()->default(null)->comment('remedio descripcion');
    $table->string('dol_mas', 2)->nullable()->default(null)->comment('dolor al masticar');
    $table->string('dol_des', 20)->nullable()->default(null)->comment('dolor descripcion');
    $table->string('san_inf', 2)->nullable()->default(null)->comment('sangrado o inflamacion');
    $table->string('san_des', 20)->nullable()->default(null)->comment('sangrado descripcion');
    $table->string('ulc_buc', 2)->nullable()->default(null)->comment('ulceras bucales');
    $table->string('ulc_fre', 20)->nullable()->default(null)->comment('ulceras frecuentes');
    $table->string('hab_boc', 2)->nullable()->default(null)->comment('habito bucal');
    $table->string('hab_cua', 100)->nullable()->default(null)->comment('habito cuantitativo');
     $table->string('obs_buc', 500)->nullable()->default(null)->comment('observaciones bucales');
    $table->string('atm_mot', 2)->nullable()->default(null)->comment('motivo de la atm');
    $table->string('atm_lat', 2)->nullable()->default(null)->comment('lateralidad de la atm');
    $table->string('atm_cha', 2)->nullable()->default(null)->comment('chasquidos o crepitaciones');
    $table->string('atm_des', 2)->nullable()->default(null)->comment('desviacion de la mandibula');
    $table->string('ocl_mo', 15)->nullable()->default(null)->comment('oclusion movil');
    $table->string('ocl_ca', 15)->nullable()->default(null)->comment('oclusion canina');
    $table->string('ocl_ovj', 15)->nullable()->default(null)->comment('oclusion overjet');
    $table->string('ocl_ovb', 15)->nullable()->default(null)->comment('oclusion overbite');
    $table->string('tej_b', 600)->nullable()->default(null)->comment('tejido blando');
    $table->timestamps();

    // Relación con Pacientes
    $table->foreign('idp')
          ->references('idp')
          ->on('pacientes')
          ->onUpdate('no action')
          ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antecedentes');
    }
};
