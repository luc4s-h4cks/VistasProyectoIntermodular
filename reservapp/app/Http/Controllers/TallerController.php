<?php

namespace App\Http\Controllers;

use App\Models\Taller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Cita;


class TallerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($handle)
    {
        $taller = Taller::where('handle',"like",$handle)->first();
        return view('taller.index', compact('taller'));
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

    public function gestionCitas()
    {
        $usu = Auth::user();

        $taller = $usu->taller;

        $resumenCitas = Cita::selectRaw('fecha, COUNT(*) as total')->groupBy('fecha')->get();

        $citas = [];

        if ($taller) {
            $citas = $taller->citas()->get();
        }

        return view('taller.gestion-citas', compact('citas', 'resumenCitas'));
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

        try {
            $usuario = Auth::user();
            $taller = $usuario->taller;

            $vehiculos = $request->vehiculos;
            $servicios = $request->servicios;

            $datos = [
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'tipo_vehiculo' => $vehiculos,
                'tipo_servicio' => $servicios,
                'info_contacto' => $request->info_contacto,
            ];

            if ($request->hasFile('imagen_taller')) {

                if ($taller && $taller->img_perfil) {
                    Storage::delete('imgTalleres/' . $taller->img_perfil);
                }

                $nombreFoto_Taller = time() . '_' . $request->file('imagen_taller')->getClientOriginalName();

                $request->file('imagen_taller')
                    ->storeAs('imgTalleres', $nombreFoto_Taller, 'public');

                $datos['img_perfil'] = $nombreFoto_Taller;
            }


            if ($request->hasFile('imagen_contacto')) {

                if ($taller && $taller->img_sec) {
                    Storage::delete('imgTalleres/' . $taller->img_sec);
                }

                $nombreFoto_Contacto = time() . '_' . $request->file('imagen_contacto')->getClientOriginalName();

                $request->file('imagen_contacto')
                    ->storeAs('imgTalleres', $nombreFoto_Contacto, 'public');

                $datos['img_sec'] = $nombreFoto_Contacto;
            }

            $usuario->taller()->updateOrCreate(
                ['id_usuario' => $usuario->id_usuario],
                $datos
            );

            return redirect()->route('mi-taller')->with('msg', 'Taller guardado correctamente');
        } catch (QueryException $e) {
            return redirect()->route('mi-taller')->with('msg', 'A ocurrido un error al intentar crear o actulizar su taller');
        }

    }

    public function buscador()
    {
        return view('dashboard');
    }


}
