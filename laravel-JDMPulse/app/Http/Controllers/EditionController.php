<?php

namespace App\Http\Controllers;

use App\Models\Edition;
use App\Http\Requests\EditionRequest;
use Illuminate\Http\Request;
use App\Http\Resources\EditionResource;
use App\Http\Librairies\ApiResponse;

class EditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $editions = Edition::all();
        return ApiResponse::success('Liste des éditions récupérée', EditionResource::collection($editions));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EditionRequest $request)
    {
        $validatedData = $request->validated();
        $edition = Edition::create($validatedData);
        return ApiResponse::created('Édition créée', new EditionResource($edition));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $edition = Edition::find($id);
        if (!$edition) {
            return ApiResponse::notFound('Édition non trouvée');
        }
        return ApiResponse::success('Détail de l\'édition', new EditionResource($edition));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditionRequest $request, string $id)
    {
        $edition = Edition::findOrFail($id);
        $validatedData = $request->validated();
        $edition->update($validatedData);
        return ApiResponse::success('Édition mise à jour', new EditionResource($edition));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $edition = Edition::find($id);
        if (!$edition) {
            return ApiResponse::notFound('Édition non trouvée');
        }
        $edition->delete();
        return ApiResponse::noContent('Édition supprimée');
    }
}