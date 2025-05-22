<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Http\Librairies\ApiResponse;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return ApiResponse::success('Liste des utilisateurs récupérée', UserResource::collection($users));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['password'] = bcrypt($validatedData['password']);
        $user = User::create($validatedData);
        return ApiResponse::created('Utilisateur créé', new UserResource($user));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return ApiResponse::notFound('Utilisateur non trouvé');
        }
        return ApiResponse::success('Détail de l\'utilisateur', new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $user = User::findOrFail($id);
            $validatedData = $request->validate([
                'first_name' => 'string|max:255',
                'last_name' => 'string|max:255',
                'date_of_birth' => 'date',
                'email' => 'string|email|max:255|unique:users,email,' . $id,
                'role' => 'string|max:255',
                'password' => 'string|min:8',
            ]);
            if (isset($validatedData['password'])) {
                $validatedData['password'] = bcrypt($validatedData['password']);
            }
            $user->update($validatedData);
            return ApiResponse::success('Utilisateur mis à jour', new UserResource($user));
        } catch (ValidationException $e) {
            return ApiResponse::validationError($e->errors());
        } catch (\Exception $e) {
            return ApiResponse::error('Internal Server Error', $e->getMessage(), 500);
        }
    }

    /**
     * Update the password of the specified resource in storage.
     */
    public function updatePassword(Request $request, string $id)
    {
        try {
            $user = User::findOrFail($id);
            $validatedData = $request->validate([
                'password' => 'required|string|min:8',
            ]);
            $validatedData['password'] = bcrypt($validatedData['password']);
            $user->update($validatedData);
            return ApiResponse::success('Mot de passe mis à jour', new UserResource($user));
        } catch (ValidationException $e) {
            return ApiResponse::validationError($e->errors());
        } catch (\Exception $e) {
            return ApiResponse::error('Internal Server Error', $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return ApiResponse::notFound('Utilisateur non trouvé');
        }
        $user->delete();
        return ApiResponse::noContent('Utilisateur supprimé');
    }
}
