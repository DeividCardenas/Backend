<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:60',
            'correo' => 'required|email|unique:usuarios,correo',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'nombre'   => $request->nombre,
            'correo'   => $request->correo,
            'password' => Hash::make($request->password),
            'activo'   => true,
        ]);

        return response()->json([
            'user'  => $user,
            'token' => $user->createToken('main')->plainTextToken
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo'   => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('correo', $request->correo)->first();

        if (! $user || ! $user->activo || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        return response()->json([
            'user'  => $user,
            'token' => $user->createToken('main')->plainTextToken
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada']);
    }
}
