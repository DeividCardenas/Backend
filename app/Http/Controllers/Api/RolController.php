<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\Request;
use App\Http\Resources\RolResource;
use App\Http\Requests\StoreRolRequest;
use App\Http\Requests\UpdateRolRequest;
use App\Services\RolService;

class RolController extends Controller
{
    protected $rolService;

    public function __construct(RolService $rolService)
    {
        $this->rolService = $rolService;
    }

    public function index()
    {
        $roles = Rol::with('permisos')->paginate(15);
        return RolResource::collection($roles);
    }

    public function store(StoreRolRequest $request)
    {
        $rol = $this->rolService->createRol($request->validated(), $request->user());
        return (new RolResource($rol->load('permisos')))
            ->response()
            ->setStatusCode(201);
    }

    public function show($id)
    {
        $rol = Rol::with('permisos', 'usuarios')->findOrFail($id);
        return new RolResource($rol);
    }

    public function update(UpdateRolRequest $request, $id)
    {
        $rol = Rol::findOrFail($id);
        $rol = $this->rolService->updateRol($rol, $request->validated(), $request->user());
        return new RolResource($rol->load('permisos'));
    }

    public function destroy($id)
    {
        $rol = Rol::findOrFail($id);
        $this->rolService->deleteRol($rol, request()->user());
        return response()->json(['message' => 'Rol eliminado correctamente']);
    }
}
