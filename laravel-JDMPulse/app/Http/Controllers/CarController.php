<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Http\Requests\CarRequest;
use Illuminate\Http\Request;
use App\Http\Resources\CarResource;
use App\Http\Resources\CarDetailsResource;
use App\Http\Librairies\ApiResponse;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = Car::with('edition')->get();
        return ApiResponse::success('Liste des voitures récupérée', CarResource::collection($cars));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CarRequest $request)
    {
        $car = Car::create($request->validated());
        return ApiResponse::created('Voiture créée', new CarResource($car));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $car = Car::find($id);
        if (!$car) {
            return ApiResponse::notFound('Voiture non trouvée');
        }
        return ApiResponse::success('Détail de la voiture', new CarResource($car));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarRequest $request, $id)
    {
        $car = Car::findOrFail($id);
        $car->update($request->validated());
        return ApiResponse::success('Voiture mise à jour', new CarResource($car));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $car = Car::find($id);
        if (!$car) {
            return ApiResponse::notFound('Voiture non trouvée');
        }
        $car->delete();
        return ApiResponse::noContent('Voiture supprimée');
    }

    /**
     * Get cars with details
     */
    public function getDetailsByCarId($id)
    {
        try {
            $car = Car::with(['edition', 'powers.engine', 'powers.engine.motorizations'])->findOrFail($id);
            return ApiResponse::success('Détails de la voiture', new CarDetailsResource($car));
        } catch (Throwable $e) {
            Log::error('Erreur getDetailsByCarId : ' . $e->getMessage());
            return ApiResponse::error('Erreur serveur', $e->getMessage(), 500);
        }
    }
}