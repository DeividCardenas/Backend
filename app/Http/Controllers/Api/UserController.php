<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::with('roles')->where('activo', true)->get();
        return response()->json($usuarios);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:60',
            'correo' => 'required|email|unique:usuarios,correo',
            'password' => 'required|min:8|confirmed',
            'activo' => 'boolean',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id_rol',
        ]);

        $user = User::create([
            'nombre' => $validated['nombre'],
            'correo' => $validated['correo'],
            'password' => Hash::make($validated['password']),
            'activo' => $validated['activo'] ?? true,
        ]);

        if (isset($validated['roles'])) {
            $user->roles()->attach($validated['roles']);
        }

        return response()->json($user->load('roles'), 201);
    }

    public function show($id)
    {
        $user = User::with('roles', 'comitesResponsable', 'comitesMiembro')->findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:60',
            'correo' => 'sometimes|required|email|unique:usuarios,correo,' . $id . ',id_usuario',
            'password' => 'sometimes|min:8|confirmed',
            'activo' => 'boolean',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id_rol',
        ]);

        $user = User::findOrFail($id);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        if (isset($validated['roles'])) {
            $user->roles()->sync($validated['roles']);
        }

        return response()->json($user->load('roles'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->update(['activo' => false]);
        return response()->json(['message' => 'Usuario desactivado correctamente']);
    }
}

