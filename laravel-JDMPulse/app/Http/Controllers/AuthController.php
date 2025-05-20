<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\LoginResource;
use App\Http\Resources\RegisterResource;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Ces identifiants ne correspondent à aucun utilisateur'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;
        $user->access_token = $token;

        return new LoginResource($user);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Token supprimé'
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        Log::info('Validated data:', $validated);

        $role = isset($validated['role']) && !empty($validated['role']) ? $validated['role'] : 'user';

        try {
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'date_of_birth' => $validated['date_of_birth'],
                'email' => $validated['email'],
                'role' => $role,
                'password' => Hash::make($validated['password']),
            ]);

            Log::info('User created:', $user->toArray());

            return (new RegisterResource($user))
                ->additional(['message' => 'User successfully registered']);
        } catch (\Exception $e) {
            Log::error('Error creating user:', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
