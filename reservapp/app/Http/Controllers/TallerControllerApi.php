<?php

namespace App\Http\Controllers;

use App\Models\Taller;
use App\Models\Usuario;
use Illuminate\Http\Request;

class TallerControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $talleres = Taller::all();
        if ($talleres->isEmpty()) {
            return response()->json(['message' => 'No se encontraron talleres'], 404);
        }
        return response()->json($talleres, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $handle)
    {
        $taller = Taller::where('handle', $handle)->first();
        if (!$taller) {
            return response()->json(['message' => 'Taller no encontrado'], 404);
        }
        return response()->json($taller, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $handle)
    {
        $taller = Taller::where('handle', $handle)->first();

        if (!$taller) {
            return response()->json(['message' => 'Taller no encontrado'], 404);
        }

        $usuario = $request->user();
        if ($taller->id_usuario !== $usuario->id_usuario && $usuario->tipo !== Usuario::ADMIN) {
            return response()->json(['message' => 'No tienes permiso para editar este taller'], 403);
        }

        $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'telefono' => 'sometimes|string|max:20',
            'email' => 'sometimes|email',
            'tipo_vehiculo' => 'sometimes|array',
            'tipo_servicio' => 'sometimes|array',
            'descripcion' => 'sometimes|string',
            'info_contacto' => 'sometimes|string',
            'ubicacion' => 'sometimes|string',
        ]);

        $taller->update($request->only([
            'nombre',
            'telefono',
            'email',
            'tipo_vehiculo',
            'tipo_servicio',
            'descripcion',
            'info_contacto',
            'ubicacion'
        ]));

        return response()->json($taller, 200);
    }

    public function destroy(Request $request, string $handle)
    {
        $taller = Taller::where('handle', $handle)->first();

        if (!$taller) {
            return response()->json(['message' => 'Taller no encontrado'], 404);
        }

        $usuario = $request->user();
        if ($taller->id_usuario !== $usuario->id_usuario && $usuario->tipo !== Usuario::ADMIN) {
            return response()->json(['message' => 'No tienes permiso para eliminar este taller'], 403);
        }

        $taller->delete();

        return response()->json(['message' => 'Taller eliminado correctamente'], 200);
    }
}
