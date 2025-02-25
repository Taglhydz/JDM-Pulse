<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = Car::all();
        return response()->json($cars);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'brand' => 'required|string|max:255',   
            'model' => 'required|string|max:255',
            'year' => 'required|string|min:4|max:9',
            'color' => 'required|string|max:50',
            'generation' => 'nullable|string',
            'image_url' => 'required|string|min:1',
            'edition_id' => 'nullable|exists:editions,id',
        ]);

        $car = Car::create($validatedData);

        return response()->json($car, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $car = Car::with('edition')->findOrFail($id);
        return response()->json($car);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $car = Car::findOrFail($id);
        

        $validatedData = $request->validate([
            'brand' => 'string|max:255',
            'model' => 'string|max:255',
            'year' => 'string|min:4|max:4',
            'edition_id' => 'exists:editions,id',
            'color' => 'string|max:50',
            'generation' => 'string',
            'image_url' => 'string',
        ]);


        $car->update($validatedData);

        return response()->json($car);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $car = Car::findOrFail($id);
        $car->delete();
        
        return response()->json(null, 204);
    }

    /**
     * Get cars with details
     */
    public function getcarsWithDetails()
    {
        try {
            $cars = Car::with(['edition', 'powers.motorization.engine'])->get();
            return response()->json($cars, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load cars with details', 'message' => $e->getMessage()], 500);
        }
    }
}