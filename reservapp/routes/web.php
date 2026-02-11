<?php

use App\Http\Controllers\TallerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';

Route::resource('taller', TallerController::class);
Route::get('mi-taller', [TallerController::class, 'miTaller'])->name('mi-taller');
Route::get('subcricion', [TallerController::class, 'tallerSubcripcion'])->name('subcripcion');

Route::post('/mi-taller', [TallerController::class, 'guardar'])->name('taller.guardar');
