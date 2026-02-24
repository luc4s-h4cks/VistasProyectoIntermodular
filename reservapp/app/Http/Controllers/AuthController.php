<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nombre_usuario' => 'required|string|max:255|unique:usuario',
            'email' => 'required|email|unique:usuario',
            'pass' => 'required|string|min:8|confirmed',
            'nombre' => 'required|string',
            'apellidos' => 'required|string',
        ]);

        $usuario = Usuario::create([
            'nombre_usuario' => $request->nombre_usuario,
            'email' => $request->email,
            'pass' => Hash::make($request->pass),
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'fecha_creacion_cuenta' => now(),
        ]);

        $token = $usuario->createToken('api-token')->plainTextToken;

        return response()->json(['usuario' => $usuario, 'token' => $token], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'pass' => 'required',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario || !Hash::check($request->pass, $usuario->pass)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        $token = $usuario->createToken('api-token')->plainTextToken;

        return response()->json(['usuario' => $usuario, 'token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente'], 200);
    }
}
