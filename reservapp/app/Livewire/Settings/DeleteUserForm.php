<?php

namespace App\Livewire\Settings;

use App\Concerns\PasswordValidationRules;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * Componente para eliminar la cuenta del usuario
 *
 * Este componente está embebido en la página de Perfil.
 * Permite al usuario eliminar permanentemente su cuenta verificando su contraseña.
 *
 * @see resources/views/livewire/settings/profile.blade.php
 */
class DeleteUserForm extends Component
{
    use PasswordValidationRules;

    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => $this->currentPasswordRules(),
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}
