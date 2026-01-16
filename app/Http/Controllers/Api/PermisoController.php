<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permiso;
use Illuminate\Http\Request;
use App\Http\Resources\PermisoResource;
use App\Http\Requests\StorePermisoRequest;
use App\Http\Requests\UpdatePermisoRequest;

class PermisoController extends Controller
{
    public function index()
    {
        $permisos = Permiso::paginate(15);
        return PermisoResource::collection($permisos);
    }

    public function store(StorePermisoRequest $request)
    {
        $validated = $request->validated();
        $permiso = Permiso::create($validated);
        return (new PermisoResource($permiso))
            ->response()
            ->setStatusCode(201);
    }

    public function show($id)
    {
        $permiso = Permiso::with('roles')->findOrFail($id);
        return new PermisoResource($permiso);
    }

    public function update(UpdatePermisoRequest $request, $id)
    {
        $validated = $request->validated();
        $permiso = Permiso::findOrFail($id);
        $permiso->update($validated);
        return new PermisoResource($permiso);
    }

    public function destroy($id)
    {
        $permiso = Permiso::findOrFail($id);
        $permiso->delete();
        return response()->json(['message' => 'Permiso eliminado correctamente']);
    }
}
