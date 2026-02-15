<?php

namespace App\Http\Controllers;

use App\Models\Taller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TallerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('taller.gestion-citas');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(Taller $taller)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Taller $taller)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Taller $taller)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Taller $taller)
    {
        //
    }

    public function miTaller()
    {
        $usuario = Auth::user();

        $taller = $usuario->taller;

        return view('taller.mi-taller')->with('taller', $taller);
    }

    public function tallerSubcripcion()
    {
        return view('taller.subcripcion');
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $usuario = Auth::user();

        $vehiculos = $request->vehiculos;
        $servicios = $request->servicios;

        $usuario->taller()->updateOrCreate(
            ['id_usuario' => $usuario->id_usuario],
            [
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'tipo_vehiculo' => $request->vehiculos,
                'tipo_servicio' => $request->servicios,
                'info_contacto' => $request->info_contacto,
                'img_perfil' => null,
                'img_sec' => null,
            ]
        );

        return back()->with('success', 'Taller guardado correctamente');
    }

}
