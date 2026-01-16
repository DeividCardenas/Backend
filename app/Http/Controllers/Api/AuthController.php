<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'nombre'   => $validated['nombre'],
            'correo'   => $validated['correo'],
            'password' => Hash::make($validated['password']),
            'activo'   => true,
        ]);

        Log::info('Nuevo usuario registrado: ' . $user->correo);

        return response()->json([
            'user'  => $user,
            'token' => $user->createToken('main')->plainTextToken
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $user = User::where('correo', $validated['correo'])->first();

        if (! $user || ! $user->activo || ! Hash::check($validated['password'], $user->password)) {
            Log::warning('Intento de login fallido: ' . $validated['correo']);
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        Log::info('Usuario logueado: ' . $user->correo);

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
        $user = $request->user();
        $user->currentAccessToken()->delete();

        Log::info('Usuario cerró sesión: ' . $user->correo);

        return response()->json(['message' => 'Sesión cerrada']);
    }
}
