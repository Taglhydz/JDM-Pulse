<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\LoginResource;
use App\Http\Resources\RegisterResource;
use App\Http\Librairies\ApiResponse;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return ApiResponse::error('Ces identifiants ne correspondent à aucun utilisateur', null, 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;
        $user->access_token = $token;

        return ApiResponse::success('Connexion réussie', new LoginResource($user));
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return ApiResponse::success('Token supprimé');
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        try {
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'date_of_birth' => $validated['date_of_birth'],
                'email' => $validated['email'],
                // Toujours 'user' : un rôle admin ne s'attribue que depuis le dashboard
                'role' => 'user',
                'password' => Hash::make($validated['password']),
            ]);

            Log::info('User created:', $user->toArray());

            return ApiResponse::created('User successfully registered', new RegisterResource($user));
        } catch (ValidationException $e) {
            return ApiResponse::validationError($e->errors());
        } catch (\Exception $e) {
            Log::error('Error creating user:', ['error' => $e->getMessage()]);
            return ApiResponse::error('Internal Server Error', $e->getMessage(), 500);
        }
    }
}
