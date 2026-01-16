<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\Request;
use App\Http\Resources\RolResource;
use App\Http\Requests\StoreRolRequest;
use App\Http\Requests\UpdateRolRequest;
use App\Services\RolService;
use App\DTOs\CreateRolDTO;
use App\DTOs\UpdateRolDTO;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;

class RolController extends Controller
{
    protected $rolService;

    public function __construct(RolService $rolService)
    {
        $this->rolService = $rolService;
    }

    public function index(): AnonymousResourceCollection
    {
        $roles = Rol::with('permisos')->paginate(15);
        return RolResource::collection($roles);
    }

    public function store(StoreRolRequest $request): JsonResponse
    {
        $dto = CreateRolDTO::fromArray($request->validated());
        $rol = $this->rolService->createRol($dto, $request->user());
        return (new RolResource($rol->load('permisos')))
            ->response()
            ->setStatusCode(201);
    }

    public function show($id): RolResource
    {
        $rol = Rol::with('permisos', 'usuarios')->findOrFail($id);
        return new RolResource($rol);
    }

    public function update(UpdateRolRequest $request, $id): RolResource
    {
        $rol = Rol::findOrFail($id);
        $dto = UpdateRolDTO::fromArray($request->validated());
        $rol = $this->rolService->updateRol($rol, $dto, $request->user());
        return new RolResource($rol->load('permisos'));
    }

    public function destroy($id): JsonResponse
    {
        $rol = Rol::findOrFail($id);
        $this->rolService->deleteRol($rol, request()->user());
        return response()->json(['message' => 'Rol eliminado correctamente']);
    }
}
