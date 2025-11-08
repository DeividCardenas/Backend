<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    public function index()
    {
        $roles = Rol::with('permisos')->get();
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'permisos' => 'array',
            'permisos.*' => 'exists:permisos,id_permiso',
        ]);

        $rol = Rol::create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
        ]);

        if (isset($validated['permisos'])) {
            $rol->permisos()->attach($validated['permisos']);
        }

        return response()->json($rol->load('permisos'), 201);
    }

    public function show($id)
    {
        $rol = Rol::with('permisos', 'usuarios')->findOrFail($id);
        return response()->json($rol);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'permisos' => 'array',
            'permisos.*' => 'exists:permisos,id_permiso',
        ]);

        $rol = Rol::findOrFail($id);
        $rol->update($validated);

        if (isset($validated['permisos'])) {
            $rol->permisos()->sync($validated['permisos']);
        }

        return response()->json($rol->load('permisos'));
    }

    public function destroy($id)
    {
        $rol = Rol::findOrFail($id);
        $rol->delete();
        return response()->json(['message' => 'Rol eliminado correctamente']);
    }
}
