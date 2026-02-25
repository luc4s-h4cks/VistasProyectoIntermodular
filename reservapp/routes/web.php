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

Route::get('buscador', [TallerController::class, 'buscador'])
->name('buscador');
Route::get("/buscador/{taller}", [TallerController::class, 'show'])->name('ver-taller');

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
Route::get('/suscripcion', [TipoSuscripcionController::class, 'index'])->name('suscripcion');
Route::post('/suscripcion/contratar', [TipoSuscripcionController::class, 'contratar'])->name('suscripcion.contratar');

Route::middleware(['mecanico', 'verified'])->group(function () {
    Route::post('/mi-taller', [TallerController::class, 'guardar'])->name('taller.guardar');
    Route::get('/citas/por-fecha', [CitaController::class, 'getCitasPorFecha'])->name('citas.por-fecha');

    Route::get('/taller/calendario-datos', function () {
        $taller = auth()->user()->taller;

        $diasBloqueados = \App\Models\Dia::where('id_taller', $taller->id_taller)
            ->where('estado', 1)
            ->pluck('fecha')
            ->values();

        $resumenCitas = \App\Models\Cita::where('id_taller', $taller->id_taller)
            ->where('estado', \App\Models\Cita::ESTADO_ACEPTADO)
            ->get()
            ->groupBy('fecha')
            ->map(fn($citas, $fecha) => ['fecha' => $fecha, 'total' => $citas->count()])
            ->values();

        return response()->json([
            'diasBloqueados' => $diasBloqueados,
            'resumenCitas' => $resumenCitas,
        ]);
    });

    Route::resource('taller', TallerController::class);
    Route::get('gestion-citas', [TallerController::class, 'gestionCitas'])->name('gestion-citas');
    Route::get('mi-taller', [TallerController::class, 'miTaller'])->name('mi-taller');
});

Route::middleware(['admin'])->group(function () {
    Route::get('/administracion-usuarios', [UsuarioController::class, 'index'])->name('admin.usuarios');
    Route::get('/administracion-coches', [CocheController::class, 'index'])->name('admin.coches');
    Route::get('/administracion-taller', [TallerController::class, 'index'])->name('admin.taller');
});


Route::get('crear-factura/{cita}', [CitaController::class, 'mostrarFactura'])->name('cita.factura');

Route::post('descargar-factura-pdf/{cita}', [CitaController::class, 'descargarFactura'])->name('cita.descargarFactura');


//acciones con las citas
Route::put('/citas/{cita}/rechazar', [CitaController::class, 'rechazarCita'])->name('cita.rechazar');
Route::put('/citas/{cita}/aceptar', [CitaController::class, 'aceptarCita'])->name('cita.aceptar');
Route::put('/citas/{cita}/proponer-fecha', [CitaController::class, 'proponerNuevaFecha'])->name('cita.proponer-fecha');
Route::put('citas/{cita}/enviar', [CitaController::class, 'enviarFactura'])->name('cita.enviarFactura');
Route::put('citas/{cita}/terminar', [CitaController::class, 'terminarCita'])->name('cita.terminar');
Route::put('citas/{cita}/pagar-taller', [CitaController::class, 'pagarTaller'])->name('cita.pagar-taller');
Route::put('citas/{cita}/pago-online', [CitaController::class, 'pagoOnline'])->name('cita.pago-online');
Route::put('citas/{cita}/marcar-pagada', [CitaController::class, 'marcaPagado'])->name('cita.marcar-pagado');

Route::get("/buscador/{taller}", [TallerController::class, 'show'])->name('ver-taller');


