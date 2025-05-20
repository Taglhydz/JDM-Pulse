<?php

namespace App\Http\Controllers;

use App\Models\Engine;
use App\Http\Requests\EngineRequest;
use Illuminate\Http\Request;
use App\Http\Resources\EngineResource;
use App\Http\Librairies\ApiResponse;

class EngineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $engines = Engine::all();
        return ApiResponse::success('Liste des moteurs récupérée', EngineResource::collection($engines));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EngineRequest $request)
    {
        $validatedData = $request->validated();
        $engine = Engine::create($validatedData);
        return ApiResponse::success('Moteur créé', new EngineResource($engine), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $engine = Engine::findOrFail($id);
        return ApiResponse::success('Détail du moteur', new EngineResource($engine));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EngineRequest $request, string $id)
    {
        $engine = Engine::findOrFail($id);
        $validatedData = $request->validated();
        $engine->update($validatedData);
        return ApiResponse::success('Moteur mis à jour', new EngineResource($engine));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $engine = Engine::findOrFail($id);
        $engine->delete();
        return ApiResponse::success('Moteur supprimé', null, 204);
    }
}