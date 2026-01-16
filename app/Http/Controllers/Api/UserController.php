<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::with('roles')->where('activo', true)->paginate(15);
        return response()->json($usuarios);
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'nombre' => $validated['nombre'],
            'correo' => $validated['correo'],
            'password' => Hash::make($validated['password']),
            'activo' => $validated['activo'] ?? true,
        ]);

        if (isset($validated['roles'])) {
            $user->roles()->attach($validated['roles']);
        }

        Log::info('Usuario creado por ' . $request->user()->correo . ': ' . $user->correo);

        return response()->json($user->load('roles'), 201);
    }

    public function show($id)
    {
        $user = User::with('roles', 'comitesResponsable', 'comitesMiembro')->findOrFail($id);
        return response()->json($user);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $validated = $request->validated();
        $user = User::findOrFail($id);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        if (isset($validated['roles'])) {
            $user->roles()->sync($validated['roles']);
        }

        Log::info('Usuario actualizado por ' . $request->user()->correo . ': ' . $user->correo);

        return response()->json($user->load('roles'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->update(['activo' => false]);

        Log::info('Usuario desactivado por ' . request()->user()->correo . ': ' . $user->correo);

        return response()->json(['message' => 'Usuario desactivado correctamente']);
    }
}
