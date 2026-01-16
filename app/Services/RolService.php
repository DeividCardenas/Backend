<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\CreateRolDTO;
use App\DTOs\UpdateRolDTO;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Exceptions\ResourceConflictException;

class RolService
{
    public function createRol(CreateRolDTO $dto, ?User $creator = null): Rol
    {
        $rol = Rol::create([
            'nombre' => $dto->nombre,
            'descripcion' => $dto->descripcion,
        ]);

        if ($dto->permisos) {
            $rol->permisos()->attach($dto->permisos);
        }

        if ($creator) {
            Log::info('Rol creado por ' . $creator->correo . ': ' . $rol->nombre);
        }

        return $rol;
    }

    public function updateRol(Rol $rol, UpdateRolDTO $dto, ?User $updater = null): Rol
    {
        $updateData = [];

        if ($dto->isDefined('nombre')) {
            $updateData['nombre'] = $dto->nombre;
        }
        if ($dto->isDefined('descripcion')) {
            $updateData['descripcion'] = $dto->descripcion;
        }

        $rol->update($updateData);

        if ($dto->isDefined('permisos')) {
            $rol->permisos()->sync($dto->permisos);
        }

        if ($updater) {
            Log::info('Rol actualizado por ' . $updater->correo . ': ' . $rol->nombre);
        }

        return $rol;
    }

    public function deleteRol(Rol $rol, ?User $deleter = null): void
    {
        if ($rol->usuarios()->count() > 0) {
            throw new ResourceConflictException("No se puede eliminar el rol '{$rol->nombre}' porque tiene usuarios asignados.");
        }

        $nombre = $rol->nombre;
        $rol->delete();

        if ($deleter) {
            Log::info('Rol eliminado por ' . $deleter->correo . ': ' . $nombre);
        }
    }
}
