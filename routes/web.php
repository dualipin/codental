<?php

use Illuminate\Support\Facades\Route;
use App\Enums\UserRolEnum;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisuserController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\OdontogramaController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\FichaPacienteController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AgendaPersonalController;
use App\Http\Controllers\FacturacionController;

//Ruta publica inicial
Route::get('/', [WelcomeController::class, 'index'])->name('nosotros');

Route::get('/test', [WelcomeController::class, 'test'])->name('test');

// --- RUTAS PÚBLICAS PARA PACIENTES (No logueados)
Route::get('/citas_p', [CitaController::class, 'pantallaIdentificacion'])->name('agenda.identificacion');;
Route::post('/verificar_paciente', [CitaController::class, 'verificarPaciente'])->name('agenda.verificar');
Route::post('/registrar_paciente-nuevo', [PacienteController::class, 'storePublico'])->name('agenda.registrar_nuevo');

// PASO 2: Calendario y Agenda (Solo accesible si pasó el paso 1)
Route::get('/programar_cita', [CitaController::class, 'pantallaAgenda'])->name('agenda.calendario');
Route::post('/confirmar_cita', [CitaController::class, 'storeDesdePaciente'])->name('citas.store');


Route::get('/agendar-cita',[CitaController::class, 'index'])->name('agendar-cita');


// Rutas para autenticación
Route::get('/login', [LoginController::class, 'show'])->name('login.show');

Route::post('/login', [LoginController::class, 'login'])->name('login');

//Route::middleware('auth.personalizado')->group(function () {
    Route::get('/agenda', [AgendaPersonalController::class, 'indexPanel'])->name('agenda');
    Route::get('/panel/agenda', [AgendaPersonalController::class, 'indexPanel'])->name('agenda.panel');

    Route::get('/pacientes/seleccionar/{idPac}', [FichaPacienteController::class, 'seleccionar'])->name('pacientes.seleccionar');
    Route::get('/pacientes/personal', [FichaPacienteController::class, 'personal'])->name('pacientes.personal');
    Route::get('/pacientes/datos', [FichaPacienteController::class, 'datos'])->name('pacientes.datos');
    Route::post('/pacientes/datos/{idPac}', [FichaPacienteController::class, 'actualizarDatos'])->name('pacientes.datos.update');
    Route::get('/pacientes/antecedentes', [FichaPacienteController::class, 'antecedentes'])->name('pacientes.antecedentes');
    Route::post('/pacientes/antecedentes/{idPac}', [FichaPacienteController::class, 'actualizarAntecedentes'])->name('pacientes.antecedentes.update');
    Route::get('/pacientes/plan-tratamiento', [FichaPacienteController::class, 'planTratamiento'])->name('pacientes.plan_tratamiento');
    Route::get('/pacientes/facturacion', [FichaPacienteController::class, 'facturacion'])->name('pacientes.facturacion');

    Route::get('/pacientes/odontograma', [OdontogramaController::class, 'index'])->name('odontograma.index');
    Route::post('/pacientes/odontograma', [OdontogramaController::class, 'store'])->name('odontograma.store');
    Route::get('/pacientes/show_pacientes', [PacienteController::class, 'index'])->name('pacientes.index');
    Route::get('/pacientes/regispacientes', [PacienteController::class, 'create'])->name('pacientes.regispacientes');
    Route::post('/agenda/citas', [AgendaPersonalController::class, 'storeCitaInterna'])->name('agenda.cita.store');
    Route::get('/caja/facturacion', [FacturacionController::class, 'index'])->name('caja.facturacion');

    Route::middleware('role.personalizado:' . UserRolEnum::ADMINISTRADOR->value . ',' . UserRolEnum::RECEPCIONISTA->value)->group(function () {
        Route::post('/caja/facturacion/abonos', [FacturacionController::class, 'registrarAbono'])->name('caja.abonos.store');
        Route::put('/caja/facturacion/abonos/{abono}', [FacturacionController::class, 'actualizarAbono'])->name('caja.abonos.update');
        Route::post('/caja/facturacion/abonos/{abono}/anular', [FacturacionController::class, 'anularAbono'])->name('caja.abonos.anular');
    });

    Route::middleware('role.personalizado:' . UserRolEnum::ADMINISTRADOR->value)->group(function () {
        Route::get('/admin/show_usuarios', [RegisuserController::class, 'showUsuarios'])->name('admin.show_usuarios');
        Route::get('/admin/registro_usuario', [RegisuserController::class, 'create'])->name('admin.registro_usuario');
        Route::post('/admin/registro_usuario', [RegisuserController::class, 'store'])->name('admin.registro_usuario.store');
        Route::view('/administracion', 'administracion')->name('administracion');
    });

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
//});




