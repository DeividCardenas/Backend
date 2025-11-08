<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        foreach ($roles as $rol) {
            if ($user->roles->contains('nombre', $rol)) {
                return $next($request);
            }
        }

        return response()->json(['message' => 'No tienes el rol necesario para acceder'], 403);
    }
}
