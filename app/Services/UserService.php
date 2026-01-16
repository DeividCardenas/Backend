<?php

namespace App\Services;

use App\DTOs\CreateUserDTO;
use App\DTOs\UpdateUserDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function createUser(CreateUserDTO $dto, ?User $creator = null): User
    {
        $user = User::create([
            'nombre' => $dto->nombre,
            'correo' => $dto->correo,
            'password' => Hash::make($dto->password),
            'activo' => $dto->activo,
        ]);

        if ($dto->roles) {
            $user->roles()->attach($dto->roles);
        }

        if ($creator) {
            Log::info('Usuario creado por ' . $creator->correo . ': ' . $user->correo);
        }

        return $user;
    }

    public function updateUser(User $user, UpdateUserDTO $dto, ?User $updater = null): User
    {
        $updateData = [];

        if ($dto->isDefined('nombre')) {
            $updateData['nombre'] = $dto->nombre;
        }
        if ($dto->isDefined('correo')) {
            $updateData['correo'] = $dto->correo;
        }
        if ($dto->isDefined('password')) {
            $updateData['password'] = Hash::make($dto->password);
        }
        if ($dto->isDefined('activo')) {
            $updateData['activo'] = $dto->activo;
        }

        $user->update($updateData);

        if ($dto->isDefined('roles')) {
            $user->roles()->sync($dto->roles);
        }

        if ($updater) {
            Log::info('Usuario actualizado por ' . $updater->correo . ': ' . $user->correo);
        }

        return $user;
    }

    public function deactivateUser(User $user, ?User $deactivator = null): void
    {
        $user->update(['activo' => false]);

        if ($deactivator) {
            Log::info('Usuario desactivado por ' . $deactivator->correo . ': ' . $user->correo);
        }
    }
}
