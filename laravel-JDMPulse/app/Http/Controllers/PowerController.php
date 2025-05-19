<?php

namespace App\Http\Controllers;

use App\Models\Power;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\PowerRequest;
use App\Http\Resources\PowerResource;

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
        return response()->json($powers, Response::HTTP_OK);
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
        return response()->json($power, Response::HTTP_CREATED);
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
            return response()->json(['message' => 'Power not found'], Response::HTTP_NOT_FOUND);
        }

        return new PowerResource($power);
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
            return response()->json(['message' => 'Power not found'], Response::HTTP_NOT_FOUND);
        }

        $power->update($request->all());
        return response()->json($power, Response::HTTP_OK);
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
            return response()->json(['message' => 'Power not found'], Response::HTTP_NOT_FOUND);
        }

        $power->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}