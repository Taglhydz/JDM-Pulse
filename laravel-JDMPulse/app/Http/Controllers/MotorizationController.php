<?php

namespace App\Http\Controllers;

use App\Models\Motorization;
use App\Http\Requests\MotorizationRequest;
use Illuminate\Http\Request;
use App\Http\Resources\MotorizationResource;
use App\Http\Librairies\ApiResponse;

class MotorizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $motorizations = Motorization::all();
        return ApiResponse::success('Liste des motorisations récupérée', MotorizationResource::collection($motorizations));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MotorizationRequest $request)
    {
        $validatedData = $request->validated();
        $motorization = Motorization::create($validatedData);
        return ApiResponse::created('Motorisation créée', new MotorizationResource($motorization));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $motorization = Motorization::with('engine')->find($id);
        if (!$motorization) {
            return ApiResponse::notFound('Motorisation non trouvée');
        }
        return ApiResponse::success('Détail de la motorisation', new MotorizationResource($motorization));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MotorizationRequest $request, string $id)
    {
        $motorization = Motorization::findOrFail($id);
        $validatedData = $request->validated();
        $motorization->update($validatedData);
        return ApiResponse::success('Motorisation mise à jour', new MotorizationResource($motorization));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $motorization = Motorization::find($id);
        if (!$motorization) {
            return ApiResponse::notFound('Motorisation non trouvée');
        }
        $motorization->delete();
        return ApiResponse::noContent('Motorisation supprimée');
    }
}