<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use App\Http\Requests\CarRequest;
use App\Http\Resources\CarResource;

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
    public function store(CarRequest $request)
    {
        $validatedData = $request->validated();

        $car = Car::create($validatedData);

        return response()->json($car, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $car = Car::findOrFail($id);
        return new CarResource($car);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarRequest $request, string $id)
    {

        $car = Car::findOrFail($id);
        

        $validatedData = $request->validated();


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