<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permiso;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    public function index()
    {
        $permisos = Permiso::all();
        return response()->json($permisos);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $permiso = Permiso::create($validated);
        return response()->json($permiso, 201);
    }

    public function show($id)
    {
        $permiso = Permiso::with('roles')->findOrFail($id);
        return response()->json($permiso);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $permiso = Permiso::findOrFail($id);
        $permiso->update($validated);
        return response()->json($permiso);
    }

    public function destroy($id)
    {
        $permiso = Permiso::findOrFail($id);
        $permiso->delete();
        return response()->json(['message' => 'Permiso eliminado correctamente']);
    }
}
