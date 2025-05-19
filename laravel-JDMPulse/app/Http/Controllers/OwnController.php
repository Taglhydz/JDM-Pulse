<?php

namespace App\Http\Controllers;

use App\Models\Own;
use App\Http\Requests\OwnRequest;
use App\Http\Resources\OwnResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OwnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $owns = Own::with(['car', 'user'])->get();
            return response()->json($owns, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OwnRequest $request)
    {
        // La validation est déjà faite par OwnRequest

        $own = Own::create($request->all());
        return response()->json($own, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        $own = Own::with(['car', 'user'])->find($id);
        
        if (!$own) {
            return response()->json(['message' => 'Ownership record not found'], Response::HTTP_NOT_FOUND);
        }

        return new OwnResource($own);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OwnRequest $request, string $id)
    {
        $own = Own::find($id);
        
        if (!$own) {
            return response()->json(['message' => 'Ownership record not found'], Response::HTTP_NOT_FOUND);
        }

        $own->update($request->all());
        return response()->json($own, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        $own = Own::find($id);
        
        if (!$own) {
            return response()->json(['message' => 'Ownership record not found'], Response::HTTP_NOT_FOUND);
        }

        $own->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}