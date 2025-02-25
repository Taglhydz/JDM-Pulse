<?php

namespace App\Http\Controllers;

use App\Models\Engine;
use Illuminate\Http\Request;

class EngineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $engines = Engine::all();
        return response()->json($engines);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'engine_name' => 'required|string|max:255',
            'architecture' => 'required|string|max:255',
            'volume' => 'required|numeric|min:0',
            'induction' => 'required|string|max:255',
            'fuel_type' => 'required|string|max:255',
        ]);

        $engine = Engine::create($validatedData);

        return response()->json($engine, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $engine = Engine::findOrFail($id);
        return response()->json($engine);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $engine = Engine::findOrFail($id);

        $validatedData = $request->validate([
            'engine_name' => 'string|max:255',
            'architecture' => 'string|max:255',
            'volume' => 'numeric|min:0',
            'induction' => 'string|max:255',           'fuel_type' => 'string|max:255',
        ]);

        $engine->update($validatedData);

        return response()->json($engine);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $engine = Engine::findOrFail($id);
        $engine->delete();
        
        return response()->json(null, 204);
    }
}