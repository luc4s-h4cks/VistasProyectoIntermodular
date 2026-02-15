<?php

namespace App\Livewire\Settings;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * Componente principal de configuración de perfil de usuario
 *
 * Este componente centraliza TODAS las configuraciones del usuario en una sola página:
 *
 * 1. Información básica: nombre, apellidos, teléfono, fecha de nacimiento
 * 2. Datos sensibles: email, nombre de usuario (requiere contraseña para modificar)
 * 3. Cambio de contraseña: con validación de contraseña actual
 * 4. Apariencia: selector de tema (claro/oscuro/sistema) - manejado por Flux UI
 * 5. Autenticación de dos factores: embebido como subcomponente
 * 6. Eliminar cuenta: si el usuario está verificado
 *
 * Anteriormente estas funcionalidades estaban separadas en:
 * - Password.php (ahora vacío)
 * - Appearance.php (ahora vacío)
 * - TwoFactor.php (ahora embebido)
 *
 * @see resources/views/livewire/settings/profile.blade.php
 */
class Profile extends Component
{
    use ProfileValidationRules, PasswordValidationRules;

    // Basic profile information
    public string $nombre = '';
    public string $apellidos = '';
    public string $telefono = '';
    public string $fecha_nacimiento = '';

    // Sensitive data
    public string $email = '';
    public string $nombre_usuario = '';
    public string $current_password = '';

    // Control for editing sensitive data
    public bool $editingSensitiveData = false;

    // Password change
    public string $password_actual = '';
    public string $password = '';
    public string $password_confirmation = '';

    // Control for editing password
    public bool $editingPassword = false;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();

        $this->nombre = $user->nombre;
        $this->apellidos = $user->apellidos;
        $this->telefono = $user->telefono ?? '';
        $this->fecha_nacimiento = $user->fecha_nacimiento ? $user->fecha_nacimiento->format('Y-m-d') : '';

        $this->email = $user->email;
        $this->nombre_usuario = $user->nombre_usuario;
    }

    /**
     * Update the basic profile information.
     */
    public function updateBasicInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate($this->basicProfileRules());

        $user->fill($validated);
        $user->save();

        $this->dispatch('profile-updated', name: $user->nombre);
    }

    /**
     * Enable editing of sensitive data.
     */
    public function enableSensitiveDataEditing(): void
    {
        $this->editingSensitiveData = true;
    }

    /**
     * Cancel editing of sensitive data.
     */
    public function cancelSensitiveDataEditing(): void
    {
        $user = Auth::user();

        // Reset to original values
        $this->email = $user->email;
        $this->nombre_usuario = $user->nombre_usuario;
        $this->current_password = '';

        $this->editingSensitiveData = false;
        $this->resetValidation();
    }

    /**
     * Update sensitive data (email, username).
     */
    public function updateSensitiveData(): void
    {
        $user = Auth::user();

        $validated = $this->validate($this->sensitiveDataRules($user->id_usuario));

        // Remove current_password from data to update
        unset($validated['current_password']);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->current_password = '';
        $this->editingSensitiveData = false;

        $this->dispatch('sensitive-data-updated');
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && ! Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return ! Auth::user() instanceof MustVerifyEmail
            || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
    }

    /**
     * Enable password editing.
     */
    public function enablePasswordEditing(): void
    {
        $this->editingPassword = true;
    }

    /**
     * Cancel password editing.
     */
    public function cancelPasswordEditing(): void
    {
        $this->password_actual = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->editingPassword = false;
        $this->resetValidation();
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(): void
    {
        $validated = $this->validate([
            'password_actual' => $this->currentPasswordRules(),
            'password' => $this->passwordRules(),
        ]);

        Auth::user()->update([
            'pass' => bcrypt($validated['password']),
        ]);

        $this->password_actual = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->editingPassword = false;

        $this->dispatch('password-updated');
    }
}
