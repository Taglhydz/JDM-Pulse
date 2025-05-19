<?php

namespace App\Http\Controllers;

use App\Models\Motorization;
use App\Http\Requests\MotorizationRequest;
use App\Http\Resources\MotorizationResource;
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
    public function store(MotorizationRequest $request)
    {
        $validatedData = $request->validated();

        $motorization = Motorization::create($validatedData);

        return response()->json($motorization, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $motorization = Motorization::with('engine')->findOrFail($id);
        return new MotorizationResource($motorization);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MotorizationRequest $request, string $id)
    {
        $motorization = Motorization::findOrFail($id);

        $validatedData = $request->validated();

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