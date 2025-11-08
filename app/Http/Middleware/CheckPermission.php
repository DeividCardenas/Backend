<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permiso)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        $tienePermiso = false;
        foreach ($user->roles as $rol) {
            if ($rol->permisos->contains('nombre', $permiso)) {
                $tienePermiso = true;
                break;
            }
        }

        if (!$tienePermiso) {
            return response()->json(['message' => 'No tienes permisos para realizar esta acción'], 403);
        }

        return $next($request);
    }
}
