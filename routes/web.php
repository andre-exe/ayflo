<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ResponsableController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\TrabajoController;
use App\Http\Controllers\EgresoController;
use App\Http\Controllers\DashboardController;

// Redirigir raíz al dashboard
Route::get('/', fn () => redirect()->route('dashboard'))
    ->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified']);

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', fn () => redirect('/dashboard'))->name('home');

    Route::get('/trabajos/por-anio', [TrabajoController::class, 'porAnio'])->name('trabajos.por_anio');


    // ============================
    // Módulos solo admin (tiene todos los permisos)
    // ============================
    Route::resource('clientes', ClienteController::class)->middleware('permission:clientes.index|clientes.create|clientes.edit|clientes.delete');
    Route::resource('responsables', ResponsableController::class)->middleware('permission:responsables.index|responsables.create|responsables.edit|responsables.delete');
    Route::resource('empleados', EmpleadoController::class)->middleware('permission:empleados.index|empleados.create|empleados.edit|empleados.delete');
    Route::resource('pagos', PagoController::class)->middleware('permission:pagos.index|pagos.create|pagos.edit|pagos.delete');
    Route::resource('bitacoras', BitacoraController::class)->middleware('permission:bitacoras.index|bitacoras.create|bitacoras.edit|bitacoras.delete');
    Route::resource('egresos', EgresoController::class)->middleware('permission:egresos.index|egresos.create|egresos.edit|egresos.delete');

    // ============================
    // TRABAJOS
    // ============================

    // Vistas generales (filtros y descargas)
    Route::get('/trabajos/pendientes', [TrabajoController::class, 'pendientes'])
        ->name('trabajos.pendientes')
        ->middleware('permission:trabajos.index');

        Route::get('/trabajos/cancelados', [TrabajoController::class, 'cancelados'])
        ->name('trabajos.cancelados')
        ->middleware('permission:trabajos.index');

    Route::get('/trabajos/completados', [TrabajoController::class, 'completados'])
        ->name('trabajos.completados')
        ->middleware('permission:trabajos.index');

    Route::get('/trabajos/en_progreso', [TrabajoController::class, 'en_progreso'])
        ->name('trabajos.en_progreso')
        ->middleware('permission:trabajos.index');

    Route::get('trabajos/{id}/archivo/{campo}', [TrabajoController::class, 'descargarArchivo'])
        ->name('trabajos.descargar-archivo')
        ->middleware('permission:trabajos.index');

    Route::delete('trabajos/{id}/archivo/{campo}', [TrabajoController::class, 'eliminarArchivo'])
        ->name('trabajos.eliminar-archivo')
        ->middleware('permission:trabajos.edit');

    // CRUD protegido con permisos
    Route::get('trabajos', [TrabajoController::class, 'index'])
        ->name('trabajos.index')
        ->middleware('permission:trabajos.index');

    Route::get('trabajos/create', [TrabajoController::class, 'create'])
        ->name('trabajos.create')
        ->middleware('permission:trabajos.create');

    Route::post('trabajos', [TrabajoController::class, 'store'])
        ->name('trabajos.store')
        ->middleware('permission:trabajos.create');

    Route::get('trabajos/{trabajo}', [TrabajoController::class, 'show'])
        ->name('trabajos.show')
        ->middleware('permission:trabajos.index');

    Route::get('trabajos/{trabajo}/edit', [TrabajoController::class, 'edit'])
        ->name('trabajos.edit')
        ->middleware('permission:trabajos.edit');

    Route::put('trabajos/{trabajo}', [TrabajoController::class, 'update'])
        ->name('trabajos.update')
        ->middleware('permission:trabajos.edit');

    Route::delete('trabajos/{trabajo}', [TrabajoController::class, 'destroy'])
    ->name('trabajos.destroy')
    ->middleware('permission:trabajos.delete');
});
