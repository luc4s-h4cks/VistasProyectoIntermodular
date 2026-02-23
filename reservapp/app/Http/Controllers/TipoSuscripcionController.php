<?php

namespace App\Http\Controllers;

use App\Models\TipoSuscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TipoSuscripcionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taller = auth()->user()->taller;
        $planes = TipoSuscripcion::all();

        $suscripcionActiva = false;
        $diasRestantes = 0;
        $planActual = null;

        if ($taller->suscripcion && $taller->fecha_fin_suscripcion) {
            $fechaFin = Carbon::parse($taller->fecha_fin_suscripcion);

            if ($fechaFin->isFuture()) {
                $suscripcionActiva = true;
                $diasRestantes = (int) Carbon::now()->diffInDays($fechaFin);
                $planActual = TipoSuscripcion::where('id_estado', $taller->suscripcion)->first();
            }
        }

        return view('taller.subcripcion', compact('taller', 'planes', 'suscripcionActiva', 'diasRestantes', 'planActual'));
    }

    public function contratar(Request $request)
    {
        $request->validate([
            'id_plan' => 'required|exists:tipo_suscripcion,id_estado',
        ]);

        $taller = auth()->user()->taller;
        $plan = TipoSuscripcion::findOrFail($request->id_plan);

        $inicio = ($taller->fecha_fin_suscripcion && Carbon::parse($taller->fecha_fin_suscripcion)->isFuture())
            ? Carbon::parse($taller->fecha_fin_suscripcion)
            : Carbon::now();

        $taller->update([
            'suscripcion' => $request->id_plan,
            'fecha_fin_suscripcion' => Carbon::now()->addMonth(),
        ]);

        return redirect()->route('suscripcion')->with('success', '¡Suscripción activada correctamente!');
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
    public function show(TipoSuscripcion $tipoSuscripcion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoSuscripcion $tipoSuscripcion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoSuscripcion $tipoSuscripcion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoSuscripcion $tipoSuscripcion)
    {
        //
    }
}
