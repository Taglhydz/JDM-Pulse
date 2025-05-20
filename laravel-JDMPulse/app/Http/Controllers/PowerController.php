<?php

namespace App\Http\Controllers;

use App\Models\Power;
use App\Http\Requests\PowerRequest;
use Illuminate\Http\Request;
use App\Http\Resources\PowerResource;
use App\Http\Librairies\ApiResponse;

class PowerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $powers = Power::with(['car', 'engine'])->get();
        return ApiResponse::success('Liste des puissances récupérée', PowerResource::collection($powers));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PowerRequest $request)
    {
        $power = Power::create($request->all());
        return ApiResponse::success('Puissance créée', new PowerResource($power), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        $power = Power::with(['car', 'engine'])->find($id);
        if (!$power) {
            return ApiResponse::error('Power not found', null, 404);
        }
        return ApiResponse::success('Détail de la puissance', new PowerResource($power));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PowerRequest $request, string $id)
    {
        $power = Power::find($id);
        if (!$power) {
            return ApiResponse::error('Power not found', null, 404);
        }
        $power->update($request->all());
        return ApiResponse::success('Puissance mise à jour', new PowerResource($power));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        $power = Power::find($id);
        if (!$power) {
            return ApiResponse::error('Power not found', null, 404);
        }
        $power->delete();
        return ApiResponse::success('Puissance supprimée', null, 204);
    }
}