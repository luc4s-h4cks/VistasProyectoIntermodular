<?php

namespace App\Http\Controllers;

use App\Models\Coche;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;


class CocheController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $miscoches = $user->coches()->paginate(4);
        //dd($miscoches);
        return view('coche.index', compact('miscoches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('coche.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([ 'marca' => 'required|string|max:255',
        'modelo' => 'required|string|max:255',
        'matricula' => 'required|string|max:20|unique:coche,matricula',
        'n_bastidor' => 'required|string|max:17|unique:coche,n_bastidor',
        'tipo_conbustible' => 'required|string|max:50',
        'img_vehiculo' => 'nullable|image|max:2048',
        ]);
        try {
            $coche = new Coche();
            $coche->marca = $request->marca;
            $coche->modelo = $request->modelo;
            $coche->matricula = $request->matricula;
            $coche->n_bastidor = $request->n_bastidor;
            $coche->tipo_conbustible = $request->tipo_conbustible;
            $coche->id_usuario = Auth::id();

            $nombreFoto = time()."_".$request->file('img_vehiculo')->getClientOriginalName();
            $coche->img_vehiculo = $nombreFoto;

            $coche->save();
            $request->file('img_vehiculo')->storeAs('public/imagenes', $nombreFoto);

            return redirect()->route('coche.index')->with('success', 'Coche creado exitosamente.');
        } catch (QueryException $e) {
            return back()->withErrors(['error' => 'Error al crear el coche: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Coche $coche)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coche $coche)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coche $coche)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coche $coche)
    {
        //
    }
}
