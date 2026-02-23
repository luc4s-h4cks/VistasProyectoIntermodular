<?php

use App\Http\Controllers\CitaController;
use App\Http\Controllers\CocheController;
use App\Http\Controllers\DiaController;
use App\Http\Controllers\TipoCombustibleController;
use App\Http\Controllers\TipoSuscripcionController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TallerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', [TallerController::class, 'buscador'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__ . '/settings.php';

Route::get('settings/mis-coches', [CocheController::class, 'miscoches'])->name('mis-coches');
Route::get('settings/mis-citas', [CitaController::class, 'misCitas'])->name('mis-citas');


Route::resource('cita', CitaController::class);
Route::resource('coche', CocheController::class);
Route::resource('dia', DiaController::class);
Route::resource('tipo-combustible', TipoCombustibleController::class);
Route::resource('tipo-suscripcion', TipoSuscripcionController::class);
Route::resource('usuario', UsuarioController::class);


Route::get('subcripcion', [TallerController::class, 'tallerSubcripcion'])->name('subcripcion');

Route::middleware(['mecanico'])->group(function () {
    Route::post('/mi-taller', [TallerController::class, 'guardar'])->name('taller.guardar');
    Route::get('/citas/por-fecha', [CitaController::class, 'getCitasPorFecha'])->name('citas.por-fecha');
    Route::resource('taller', TallerController::class);
    Route::get('gestion-citas', [TallerController::class, 'gestionCitas'])->name('gestion-citas');
    Route::get('mi-taller', [TallerController::class, 'miTaller'])->name('mi-taller');
});

Route::middleware(['admin'])->group(function () {
    Route::get('/administracion-usuarios', [UsuarioController::class, 'index'])->name('admin.usuarios');
});


Route::get('crear-factura/{cita}', [CitaController::class, 'mostrarFactura'])->name('cita.factura');

Route::post('descargar-factura-pdf/{cita}', [CitaController::class, 'descargarFactura'])->name('cita.descargarFactura');


//acciones con las citas
Route::put('/citas/{cita}/rechazar', [CitaController::class, 'rechazarCita'])->name('cita.rechazar');
Route::put('/citas/{cita}/aceptar', [CitaController::class, 'aceptarCita'])->name('cita.aceptar');
Route::put('/citas/{cita}/proponer-fecha', [CitaController::class, 'proponerNuevaFecha'])->name('cita.proponer-fecha');
Route::put('citas/{cita}/enviar', [CitaController::class, 'enviarFactura'])->name('cita.enviarFactura');

Route::get("/buscador/{taller}", [TallerController::class, 'index'])->name('buscador');
