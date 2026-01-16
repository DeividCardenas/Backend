<?php

namespace App\Services;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class RolService
{
    public function createRol(array $data, ?User $creator = null): Rol
    {
        $rol = Rol::create([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null,
        ]);

        if (isset($data['permisos'])) {
            $rol->permisos()->attach($data['permisos']);
        }

        if ($creator) {
            Log::info('Rol creado por ' . $creator->correo . ': ' . $rol->nombre);
        }

        return $rol;
    }

    public function updateRol(Rol $rol, array $data, ?User $updater = null): Rol
    {
        $rol->update($data);

        if (isset($data['permisos'])) {
            $rol->permisos()->sync($data['permisos']);
        }

        if ($updater) {
            Log::info('Rol actualizado por ' . $updater->correo . ': ' . $rol->nombre);
        }

        return $rol;
    }

    public function deleteRol(Rol $rol, ?User $deleter = null): void
    {
        $nombre = $rol->nombre;
        $rol->delete();

        if ($deleter) {
            Log::info('Rol eliminado por ' . $deleter->correo . ': ' . $nombre);
        }
    }
}
