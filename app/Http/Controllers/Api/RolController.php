<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreRolRequest;
use App\Http\Requests\UpdateRolRequest;

class RolController extends Controller
{
    public function index()
    {
        $roles = Rol::with('permisos')->paginate(15);
        return response()->json($roles);
    }

    public function store(StoreRolRequest $request)
    {
        $validated = $request->validated();

        $rol = Rol::create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
        ]);

        if (isset($validated['permisos'])) {
            $rol->permisos()->attach($validated['permisos']);
        }

        Log::info('Rol creado por ' . $request->user()->correo . ': ' . $rol->nombre);

        return response()->json($rol->load('permisos'), 201);
    }

    public function show($id)
    {
        $rol = Rol::with('permisos', 'usuarios')->findOrFail($id);
        return response()->json($rol);
    }

    public function update(UpdateRolRequest $request, $id)
    {
        $validated = $request->validated();

        $rol = Rol::findOrFail($id);
        $rol->update($validated);

        if (isset($validated['permisos'])) {
            $rol->permisos()->sync($validated['permisos']);
        }

        Log::info('Rol actualizado por ' . $request->user()->correo . ': ' . $rol->nombre);

        return response()->json($rol->load('permisos'));
    }

    public function destroy($id)
    {
        $rol = Rol::findOrFail($id);
        $nombre = $rol->nombre;
        $rol->delete();

        Log::info('Rol eliminado por ' . request()->user()->correo . ': ' . $nombre);

        return response()->json(['message' => 'Rol eliminado correctamente']);
    }
}
