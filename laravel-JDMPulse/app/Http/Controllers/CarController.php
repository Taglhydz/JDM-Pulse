<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use App\Http\Requests\CarRequest;
use App\Http\Resources\CarResource;
use App\Http\Resources\CarDetailsResource;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = Car::all();
        return CarResource::collection($cars);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CarRequest $request)
    {
        $car = Car::create($request->validated());
        return new CarResource($car);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $car = Car::findOrFail($id);
        return new CarResource($car);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarRequest $request, $id)
    {
        $car = Car::findOrFail($id);
        $car->update($request->validated());
        return new CarResource($car);
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
    public function getDetailsByCarId($id)
    {
        try {
            $car = Car::with(['edition', 'powers.engine', 'powers.engine.motorizations'])->findOrFail($id);
            return new CarDetailsResource($car);
        } catch (\Throwable $e) {
            \Log::error('Erreur getDetailsByCarId : ' . $e->getMessage());
            return response()->json([
                'error' => 'Erreur serveur',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }
}