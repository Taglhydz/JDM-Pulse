<?php

namespace App\Http\Controllers;

use App\Models\Own;
use App\Http\Requests\OwnRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\OwnResource;
use App\Http\Resources\CarResource;
use App\Http\Librairies\ApiResponse;

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
            return ApiResponse::success('Liste des possessions récupérée', OwnResource::collection($owns));
        } catch (\Exception $e) {
            return ApiResponse::error('Internal Server Error', $e->getMessage(), 500);
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
        try {
            $own = Own::create($request->all());
            return ApiResponse::success('Possession créée', new OwnResource($own), 201);
        } catch (ValidationException $e) {
            return ApiResponse::validationError($e->errors());
        } catch (\Exception $e) {
            return ApiResponse::error('Internal Server Error', $e->getMessage(), 500);
        }
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
            return ApiResponse::error('Ownership record not found', null, 404);
        }
        return ApiResponse::success('Détail de la possession', new OwnResource($own));
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
        try {
            $own = Own::find($id);
            if (!$own) {
                return ApiResponse::error('Ownership record not found', null, 404);
            }
            $own->update($request->all());
            return ApiResponse::success('Possession mise à jour', new OwnResource($own));
        } catch (ValidationException $e) {
            return ApiResponse::validationError($e->errors());
        } catch (\Exception $e) {
            return ApiResponse::error('Internal Server Error', $e->getMessage(), 500);
        }
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
            return ApiResponse::error('Ownership record not found', null, 404);
        }
        $own->delete();
        return ApiResponse::success('Possession supprimée', null, 204);
    }

    /**
     * Get all cars owned by a specific user
     */
    public function getCarsByUserId($userId)
    {
        $owns = Own::with('car')->where('user_id', $userId)->get();
        $cars = $owns->pluck('car')->filter();
        return ApiResponse::success('Voitures de l\'utilisateur récupérées', CarResource::collection($cars));
    }
}