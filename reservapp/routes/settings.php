<?php

use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

/**
 * NOTA: Las funcionalidades de Password, Appearance y Two-Factor
 * ahora están integradas en Profile.php
 * 
 * - Password: Cambio de contraseña dentro de Profile
 * - Appearance: Selector de tema dentro de Profile
 * - Two-Factor: Componente embebido en Profile
 */

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::livewire('settings/profile', Profile::class)->name('profile.edit');
});

// Las siguientes rutas están comentadas porque la funcionalidad se movió a Profile
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::livewire('settings/password', Password::class)->name('user-password.edit');
//     Route::livewire('settings/appearance', Appearance::class)->name('appearance.edit');
//     Route::livewire('settings/two-factor', TwoFactor::class)->name('two-factor.show');
// });
