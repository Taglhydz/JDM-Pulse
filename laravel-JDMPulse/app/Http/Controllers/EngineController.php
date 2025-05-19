<?php

namespace App\Http\Controllers;

use App\Models\Engine;
use Illuminate\Http\Request;
use App\Http\Requests\EngineRequest;
use App\Http\Resources\EngineResource;

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
    public function store(EngineRequest $request)
    {
        $validatedData = $request->validated();

        $engine = Engine::create($validatedData);

        return response()->json($engine, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $engine = Engine::findOrFail($id);
        return new EngineResource($engine);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EngineRequest $request, string $id)
    {
        $engine = Engine::findOrFail($id);

        $validatedData = $request->validated();

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