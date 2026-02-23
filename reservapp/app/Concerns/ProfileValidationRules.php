<?php

namespace App\Concerns;

use App\Models\Usuario;
use Illuminate\Validation\Rule;

trait ProfileValidationRules
{
    /**
     * Get the validation rules used to validate basic user profile info.
     *
     * @return array<string, array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>>
     */
    protected function basicProfileRules(): array
    {
        return [
            'nombre' => $this->nameRules(),
            'apellidos' => ['required', 'string', 'max:32'],
            'telefono' => ['nullable', 'string', 'max:12'],
            'fecha_nacimiento' => ['required', 'date', 'before:today'],
            'img_perfil' => $this->imageRules(),
        ];
    }
    protected function basicProfileMessages(): array
    {
        return [
            'nombre.required'         => 'El nombre es obligatorio.',
            'nombre.max'              => 'El nombre no puede superar los 32 caracteres.',

            'apellidos.required'      => 'Los apellidos son obligatorios.',
            'apellidos.max'           => 'Los apellidos no pueden superar los 32 caracteres.',

            'telefono.required'       => 'El teléfono es obligatorio.',
            'telefono.max'            => 'El teléfono no puede superar los 12 caracteres.',

            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date'   => 'La fecha de nacimiento no es válida.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',

            'img_perfil.image'        => 'El archivo debe ser una imagen.',
            'img_perfil.mimes'        => 'La imagen debe ser jpeg, png, webp o jpg.',
            'img_perfil.max'          => 'La imagen no puede superar los 5MB.',
        ];
    }

    /**
     * Get the validation rules used to validate sensitive user data.
     *
     * @return array<string, array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>>
     */
    protected function sensitiveDataRules(?int $userId = null): array
    {
        return [
            'email' => $this->emailRules($userId),
            'nombre_usuario' => $this->usernameRules($userId),
            'current_password' => ['required', 'string', 'current_password'],
        ];
    }
    protected function sensitiveDataMessages(): array
    {
        return [
            'email.required'                    => 'El correo electrónico es obligatorio.',
            'email.email'                       => 'El formato del correo no es válido.',
            'email.unique'                      => 'Este correo ya está registrado.',
            'email.max'                         => 'El correo no puede superar los 64 caracteres.',

            'nombre_usuario.required'           => 'El nombre de usuario es obligatorio.',
            'nombre_usuario.unique'             => 'Este nombre de usuario ya está en uso.',
            'nombre_usuario.max'                => 'El nombre de usuario no puede superar los 32 caracteres.',

            'current_password.required'         => 'Debes introducir tu contraseña actual.',
            'current_password.current_password' => 'La contraseña actual no es correcta.',
        ];
    }

    /**
     * Get the validation rules used to validate user profiles.
     *
     * @return array<string, array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>>
     */
    protected function profileRules(?int $userId = null): array
    {
        return [
            'nombre' => $this->nameRules(),
            'email' => $this->emailRules($userId),
        ];
    }

    /**
     * Get the validation rules used to validate user names.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function nameRules(): array
    {
        return ['required', 'string', 'max:32'];
    }

    /**
     * Get the validation rules used to validate user emails.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function emailRules(?int $userId = null): array
    {
        return [
            'required',
            'string',
            'email',
            'max:64',
            $userId === null
                ? Rule::unique(Usuario::class, 'email')
                : Rule::unique(Usuario::class, 'email')->ignore($userId, 'id_usuario'),
        ];
    }

    /**
     * Get the validation rules used to validate usernames.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function usernameRules(?int $userId = null): array
    {
        return [
            'required',
            'string',
            'max:32',
            $userId === null
                ? Rule::unique(Usuario::class, 'nombre_usuario')
                : Rule::unique(Usuario::class, 'nombre_usuario')->ignore($userId, 'id_usuario'),
        ];
    }

    /**
     * Get the validation rules used to validate profile images.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function imageRules(): array
    {
        return ['nullable', 'image', 'mimes:jpeg,png,webp,jpg', 'max:5120'];
    }
}
