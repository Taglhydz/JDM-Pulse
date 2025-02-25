<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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

        return response()->json([
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'date_of_birth' => $user->date_of_birth,
                'email' => $user->email,
            ],
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
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

        try {
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'date_of_birth' => $validated['date_of_birth'],
                'email' => $validated['email'],
                'role' => 'user',
                'password' => Hash::make($validated['password']),
            ]);

            Log::info('User created:', $user->toArray());

            return response()->json([
                'message' => 'User successfully registered',
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating user:', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
