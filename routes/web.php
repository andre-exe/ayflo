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

    // Rutas para tus controladores
    Route::resource('clientes', App\Http\Controllers\ClienteController::class);
    Route::resource('responsables', ResponsableController::class);
    Route::resource('empleados', App\Http\Controllers\EmpleadoController::class);
    Route::resource('pagos', PagoController::class);
    Route::resource('bitacoras', BitacoraController::class);
    Route::resource('trabajos', TrabajoController::class);
    Route::resource('egresos', EgresoController::class);
});
