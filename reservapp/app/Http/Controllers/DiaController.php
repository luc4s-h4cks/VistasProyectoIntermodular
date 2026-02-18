<?php

namespace App\Http\Controllers;

use App\Models\Dia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(Dia $dia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dia $dia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dia $dia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dia $dia)
    {
        //
    }

    public function existeDia($tallerId, $fecha){
        $dia = Dia::where('id_taller', $tallerId)->whereDate('fecha', $fecha)->first();

        if(!$dia){
            $dia = Dia::create([
                'id_taller' => $tallerId,
                'fecha' => $fecha,
                'estado' => 0,
            ]);
        }

        return $dia;

    }
}
