<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Dia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $miscitas = $user->citas;
        return view('cita.index', compact('miscitas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cita.create');
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
    public function show(Cita $cita)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cita $cita)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cita $cita)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cita $cita)
    {
        //
    }

    public function getCitasPorFecha(Request $request)
    {
        $fecha = $request->fecha;

        $citas = Cita::whereDate('fecha', $fecha)
            ->whereIn('estado', [1])
            ->with(['coche.usuario'])
            ->get();

        return view('taller.gestion-citas', compact('citas', 'fecha'));
    }

    public function rechazarCita(Cita $cita)
    {
        $cita->estado = Cita::ESTADO_RECHAZADO_POR_TALLER;
        $cita->save();

        return redirect()->back()->with('Seccess', "Cita rechazada");
    }

    public function aceptarCita(Cita $cita)
    {
        $cita->estado = Cita::ESTADO_ACEPTADO;
        $cita->save();

        return redirect()->back()->with('Seccess', "Cita rechazafa");
    }

    public function proponerNuevaFecha(Cita $cita, Request $request)
    {
        $request->validate(
            [
                'nueva_fecha' => 'required|date| after:today'
            ],
            [
                'nueva_fecha.required' => 'Debes seleccionar una fecha.',
                'nueva_fecha.date' => 'La fecha no es válida.',
                'nueva_fecha.after' => 'La fecha debe ser posterior a hoy.'
            ]
        );



        $dia = Dia::firstOrCreate(
            ['fecha' => $request->nueva_fecha, 'id_taller' => auth()->user()->taller->id_taller],
            ['estado' => 0]
        );

        $cita->fecha = $dia->fecha;
        $cita->estado = Cita::ESTADO_FECHA_PROPUESTA;

        $cita->save();

        return redirect()->route('gestion-citas')
            ->with('success', 'Nueva fecha propuesta correctamente');
    }

    public function mostrarFactura(Cita $cita){
        return view('taller.factura')->with('cita', $cita);
    }

    public function enviarFactura(Cita $cita){
        $cita->estado = Cita::ESTADO_ESPERANDO_PAGO;
        $cita->save();
        return redirect()->route('gestion-citas')->with('mensaje', 'Factura enciada');
    }

}
