<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permiso;
use Illuminate\Http\Request;
use App\Http\Resources\PermisoResource;
use App\Http\Requests\StorePermisoRequest;
use App\Http\Requests\UpdatePermisoRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;

class PermisoController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $permisos = Permiso::paginate(15);
        return PermisoResource::collection($permisos);
    }

    public function store(StorePermisoRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $permiso = Permiso::create($validated);
        return (new PermisoResource($permiso))
            ->response()
            ->setStatusCode(201);
    }

    public function show($id): PermisoResource
    {
        $permiso = Permiso::with('roles')->findOrFail($id);
        return new PermisoResource($permiso);
    }

    public function update(UpdatePermisoRequest $request, $id): PermisoResource
    {
        $validated = $request->validated();
        $permiso = Permiso::findOrFail($id);
        $permiso->update($validated);
        return new PermisoResource($permiso);
    }

    public function destroy($id): JsonResponse
    {
        $permiso = Permiso::findOrFail($id);
        $permiso->delete();
        return response()->json(['message' => 'Permiso eliminado correctamente']);
    }
}
