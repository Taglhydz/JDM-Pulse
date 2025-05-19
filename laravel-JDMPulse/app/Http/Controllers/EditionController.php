<?php

namespace App\Http\Controllers;

use App\Models\Edition;
use App\Http\Requests\EditionRequest;
use App\Http\Resources\EditionResource;
use Illuminate\Http\Request;

class EditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $editions = Edition::all();
        return response()->json($editions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EditionRequest $request)
    {
        $validatedData = $request->validated();

        $edition = Edition::create($validatedData);

        return response()->json($edition, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $edition = Edition::findOrFail($id);
        return new EditionResource($edition);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditionRequest $request, string $id)
    {
        $edition = Edition::findOrFail($id);

        $validatedData = $request->validated();

        $edition->update($validatedData);

        return response()->json($edition);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $edition = Edition::findOrFail($id);
        $edition->delete();
        
        return response()->json(null, 204);
    }
}