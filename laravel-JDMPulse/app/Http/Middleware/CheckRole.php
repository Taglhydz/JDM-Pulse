<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
        $userRole = $user ? $user->role : null;

        if (!$user || (count($roles) > 0 && !in_array($user->role, $roles))) {
            return response()->json([
                'error' => 'Forbidden',
                'user_role' => $userRole,
                'expected_roles' => $roles
            ], 403);
        }

        return $next($request);
    }
}
