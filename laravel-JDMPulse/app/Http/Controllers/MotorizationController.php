<?php

namespace App\Http\Controllers;

use App\Models\Motorization;
use Illuminate\Http\Request;

class MotorizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $motorizations = Motorization::all();
        return response()->json($motorizations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'power' => 'required|numeric|min:0',
            'torque' => 'required|numeric|min:0',
            'consumption' => 'required|numeric|min:0',
            'engine_id' => 'required|exists:engines,id',
        ]);

        $motorization = Motorization::create($validatedData);

        return response()->json($motorization, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $motorization = Motorization::with('engine')->findOrFail($id);
        return response()->json($motorization);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $motorization = Motorization::findOrFail($id);

        $validatedData = $request->validate([
            'power' => 'numeric|min:0',
            'torque' => 'numeric|min:0',
            'consumption' => 'numeric|min:0',
            'engine_id' => 'exists:engines,id',
        ]);

        $motorization->update($validatedData);

        return response()->json($motorization);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $motorization = Motorization::findOrFail($id);
        $motorization->delete();
        
        return response()->json(null, 204);
    }
}