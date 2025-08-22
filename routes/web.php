<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ResponsableController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\TrabajoController;
use App\Http\Controllers\EgresoController;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
]);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Vista principal después del login
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/home', function () {
    return redirect('/dashboard');
})->name('home');


    Route::resource('clientes', App\Http\Controllers\ClienteController::class);
    Route::resource('responsables', ResponsableController::class);
    Route::resource('empleados', App\Http\Controllers\EmpleadoController::class);
    Route::resource('pagos', PagoController::class);
    Route::resource('bitacoras', BitacoraController::class);

    // RUTAS ESPECÍFICAS DE TRABAJOS - ANTES DEL RESOURCE
    Route::get('trabajos/{id}/archivo/{campo}', [TrabajoController::class, 'descargarArchivo'])
         ->name('trabajos.descargar-archivo');
    Route::delete('trabajos/{id}/archivo/{campo}', [TrabajoController::class, 'eliminarArchivo'])
         ->name('trabajos.eliminar-archivo');
    Route::get('/trabajos/test-descarga', [TrabajoController::class, 'testDescarga']);

    // RESOURCE DE TRABAJOS - DESPUÉS DE LAS RUTAS ESPECÍFICAS
    Route::resource('trabajos', TrabajoController::class);

    Route::resource('egresos', EgresoController::class);
});
