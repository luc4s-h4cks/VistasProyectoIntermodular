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
            'apellidos' => ['required', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:12'],
            'fecha_nacimiento' => ['required', 'date', 'before:today'],
            'img_perfil' => $this->imageRules(),
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
        return ['required', 'string', 'max:255'];
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
            'max:255',
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
            'max:255',
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
