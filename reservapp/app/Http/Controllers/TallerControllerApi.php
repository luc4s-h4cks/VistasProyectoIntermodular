<?php

namespace App\Http\Controllers;

use App\Models\Taller;
use Illuminate\Http\Request;

class TallerControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $talleres = Taller::all();
        if($talleres->isEmpty()){
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
        if(!$taller){
            return response()->json(['message' => 'Taller no encontrado'], 404);
        }
        return response()->json($taller, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
