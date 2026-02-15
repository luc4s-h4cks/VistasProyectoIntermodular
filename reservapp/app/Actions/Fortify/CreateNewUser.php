<?php

namespace App\Actions\Fortify;

use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    public function create(array $input)
    {
        Validator::make($input, [
            'nombre' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'nombre_usuario' => ['required', 'string', 'max:255', 'unique:usuario,nombre_usuario'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:usuario,email'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'fecha_nacimiento' => ['required', 'date'],
            'tipo' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ])->validate();

        return Usuario::create([
            'nombre' => $input['nombre'],
            'apellidos' => $input['apellidos'],
            'nombre_usuario' => $input['nombre_usuario'],
            'email' => $input['email'],
            'telefono' => $input['telefono'] ?? null,
            'fecha_nacimiento' => $input['fecha_nacimiento'],
            'tipo' => $input['tipo'],
            'pass' => Hash::make($input['password']),
            'fecha_creacion_cuenta' => now(),
        ]);
    }
}
