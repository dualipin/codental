<?php

use App\Enums\UserRolEnum;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AgendaPersonalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\FacturacionController;
use App\Http\Controllers\FichaPacienteController;
use App\Http\Controllers\HistoriaClinicaController;
use App\Http\Controllers\OdontogramaController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\PresupuestoPdfController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Middleware\CheckFinancialAccess;
use App\Http\Middleware\InertiaAgendaMiddleware;
use App\Http\Middleware\InertiaAppMiddleware;
use Illuminate\Support\Facades\Route;

//Ruta publica inicial
Route::get('/', [WelcomeController::class, 'index'])->name('index');

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'show'])->name('login.show');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas para agendar citas
Route::prefix('agendar-cita')->controller(CitaController::class)->group(function () {
    Route::get('/', 'index')->name('agendar-cita');
    Route::get('/identificar-paciente', 'identificarPacienteShow')->name('agendar-cita.identificar-paciente.show');
    Route::post('/identificar-paciente', 'identificarPaciente')->name('agendar-cita.identificar-paciente');

    // Rutas con inertia
    Route::middleware(['web', InertiaAgendaMiddleware::class])->group(function () {
        Route::get('/registrar-paciente', 'registrarPacienteShow')->name('agendar-cita.paciente-nuevo.show');
        Route::post('/registrar-paciente', 'registrarPaciente')->name('agendar-cita.registrar-paciente');
        Route::get('/calendario', 'calendarioShow')->name('agendar-cita.calendario.show');
        Route::post('/calendario', 'store')->name('agendar-cita.calendario.store');
        Route::get('/confirmacion/{cita}', 'confirmacion')->name('agendar-cita.confirmacion');
    });

    // Rutas para descarga de PDF
    Route::get('/confirmacion/{cita}/pdf', 'descargarPdf')->name('agendar-cita.descargar-pdf');
});

// rutas de la app
Route::middleware(['auth.personalizado'])->prefix('app')->group(function () {
    Route::prefix('agenda')->controller(AgendaController::class)->group(function () {
        Route::get('/', 'index')->name('agenda');
        Route::post('/citas', 'store')->name('agenda.citas.store');
        Route::get('/cita/{cita}', 'confirmar')->name('agenda.citas.confirmar');
        Route::patch('/cita/{cita}/confirmar', 'confirmarCita')->name('agenda.citas.confirmar.update');
        Route::patch('/cita/{cita}/cancelar', 'cancelarCita')->name('agenda.citas.cancelar');
    });

    // Rutas para Evolución Clínica y Recetas
    Route::post('/citas/{cita}/evolucion', [\App\Http\Controllers\EvolucionClinicaController::class, 'store'])->name('evolucion.store');
    Route::get('/recetas/{receta}/pdf/download', [\App\Http\Controllers\RecetaController::class, 'downloadPdf'])->name('recetas.pdf.download');
    Route::get('/recetas/{receta}/pdf/stream', [\App\Http\Controllers\RecetaController::class, 'streamPdf'])->name('recetas.pdf.stream');

    Route::middleware('role.personalizado:' . UserRolEnum::ADMINISTRADOR->value . ',' . UserRolEnum::RECEPCIONISTA->value)->group(function () {
        // CRM Dashboard de Recepción
        Route::get('/recepcion/dashboard', [\App\Http\Controllers\DashboardRecepcionController::class, 'index'])->name('recepcion.dashboard');
    });

    Route::prefix('pacientes')->controller(PacienteController::class)->group(function () {
        Route::get('/', 'index')->name('pacientes.index');
        Route::get('/create', 'create')->name('pacientes.create');
        Route::post('/create', 'store')->name('pacientes.store');
        Route::get('/edit/{paciente}', 'edit')->name('pacientes.edit');
        Route::patch('/edit/{paciente}', 'update')->name('pacientes.update');
        Route::delete('/delete/{paciente}', 'destroy')->name('pacientes.destroy');
        Route::get('/show/{paciente}', 'show')->name('pacientes.show');
        Route::post('/verify/{paciente}', 'verify')->name('pacientes.verify');
    });

    Route::get('/pacientes/{paciente}/historia-clinica', [HistoriaClinicaController::class, 'edit'])
        ->name('pacientes.historia-clinica.edit');
    Route::patch('/pacientes/{paciente}/historia-clinica', [HistoriaClinicaController::class, 'update'])
        ->name('pacientes.historia-clinica.update');

    Route::get('/pacientes/{paciente}/odontograma/inicial', [OdontogramaController::class, 'inicial'])
        ->name('pacientes.odontograma.inicial');
    Route::get('/pacientes/{paciente}/odontograma/final', [OdontogramaController::class, 'final'])
        ->name('pacientes.odontograma.final');
    Route::post('/pacientes/{paciente}/odontograma', [OdontogramaController::class, 'guardar'])
        ->name('pacientes.odontograma.guardar');

    Route::middleware(CheckFinancialAccess::class)->prefix('caja')->controller(FacturacionController::class)->group(function () {
        Route::get('/facturacion', 'index')->name('caja.facturacion');
        Route::get('/facturacion/buscar-pacientes', 'buscarPacientes')->name('caja.facturacion.buscar-pacientes');
        Route::post('/facturacion/abonos', 'registrarAbono')->name('caja.abonos.store');
        Route::post('/facturacion/abonos/{movimiento}/anular', 'anularAbono')->name('caja.abonos.anular');
        Route::get('/facturacion/estado-cuenta/{pacienteId}', 'estadoCuenta')->name('caja.estado-cuenta');
    });

    Route::get('/presupuestos/{presupuesto}/pdf', [PresupuestoPdfController::class, 'download'])
        ->name('presupuestos.pdf.download');

    // Administración exclusiva para admin
    Route::middleware('role.personalizado:' . UserRolEnum::ADMINISTRADOR->value)
        ->prefix('admin')
        ->group(function () {
            Route::prefix('usuarios')->controller(UserController::class)->group(function () {
                Route::get('/', 'index')->name('usuarios');
                Route::get('/create', 'create')->name('usuarios.create');
                Route::post('/create', 'store')->name('usuarios.store');
                Route::get('/edit/{user}', 'edit')->name('usuarios.edit');
                Route::patch('/edit/{user}', 'update')->name('usuarios.update');
                Route::delete('/delete/{user}', 'destroy')->name('usuarios.destroy');
            });

            Route::get('/profile/{user}', [UserController::class, 'profile'])->name('usuarios.profile');
            Route::get('/settings/{user}', [UserController::class, 'settings'])->name('usuarios.settings');
            Route::patch('/settings/{user}', [UserController::class, 'updateSettings'])
                ->name('usuarios.settings.update');
        });
});


//Route::get('/agendar-cita', [CitaController::class, 'index'])->name('agendar-cita');

// Historia Clinica
//Route::prefix('historia-clinica')->controller(HistoriaClinicaController::class)->group(function () {
//    Route::get('/{idPaciente}', 'index')->name('historia-clinica.index');
//    Route::get('/{idPaciente}/consultas', 'consultas')->name('historia-clinica.consultas');
//    Route::get('/{idPaciente}/consultas/{idConsulta}', 'detalleConsulta')->name('historia-clinica.consulta.detalle');
//    Route::post('/{idPaciente}/consultas', 'storeConsulta')->name('historia-clinica.consulta.store');
//    Route::put('/{idPaciente}/consultas/{idConsulta}', 'updateConsulta')->name('historia-clinica.consulta.update');
//});


////Route::middleware('auth.personalizado')->group(function () {
////Route::get('/agenda', [AgendaPersonalController::class, 'indexPanel'])->name('agenda');
//Route::get('/panel/agenda', [AgendaPersonalController::class, 'indexPanel'])->name('agenda.panel');
//
//
//Route::post('/pacientes/datos/{idPac}', [FichaPacienteController::class, 'actualizarDatos'])->name(
//    'pacientes.datos.update'
//);
////Route::get('/pacientes/antecedentes', [FichaPacienteController::class, 'antecedentes'])->name('pacientes.antecedentes');
//Route::post('/pacientes/antecedentes/{idPac}', [FichaPacienteController::class, 'actualizarAntecedentes'])->name(
//    'pacientes.antecedentes.update'
//);
//Route::get('/pacientes/plan-tratamiento', [FichaPacienteController::class, 'planTratamiento'])->name(
//    'pacientes.plan_tratamiento'
//);
//Route::get('/pacientes/facturacion', [FichaPacienteController::class, 'facturacion'])->name('pacientes.facturacion');
//
//Route::get('/pacientes/odontograma', [OdontogramaController::class, 'index'])->name('odontograma.index');
//Route::post('/pacientes/odontograma', [OdontogramaController::class, 'store'])->name('odontograma.store');
//Route::get('/pacientes/show_pacientes', [PacienteController::class, 'index'])->name('pacientes.index');
//Route::get('/pacientes/regispacientes', [PacienteController::class, 'create'])->name('pacientes.regispacientes');
//Route::post('/agenda/citas', [AgendaPersonalController::class, 'storeCitaInterna'])->name('agenda.cita.store');
//Route::get('/caja/facturacion', [FacturacionController::class, 'index'])->name('caja.facturacion');
//
//Route::middleware(
//    'role.personalizado:' . UserRolEnum::ADMINISTRADOR->value . ',' . UserRolEnum::RECEPCIONISTA->value
//)->group(function () {
//    Route::post('/caja/facturacion/abonos', [FacturacionController::class, 'registrarAbono'])->name(
//        'caja.abonos.store'
//    );
//    Route::put('/caja/facturacion/abonos/{abono}', [FacturacionController::class, 'actualizarAbono'])->name(
//        'caja.abonos.update'
//    );
//    Route::post('/caja/facturacion/abonos/{abono}/anular', [FacturacionController::class, 'anularAbono'])->name(
//        'caja.abonos.anular'
//    );
//});
