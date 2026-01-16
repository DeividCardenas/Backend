<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function createUser(array $data, ?User $creator = null): User
    {
        $user = User::create([
            'nombre' => $data['nombre'],
            'correo' => $data['correo'],
            'password' => Hash::make($data['password']),
            'activo' => $data['activo'] ?? true,
        ]);

        if (isset($data['roles'])) {
            $user->roles()->attach($data['roles']);
        }

        if ($creator) {
            Log::info('Usuario creado por ' . $creator->correo . ': ' . $user->correo);
        }

        return $user;
    }

    public function updateUser(User $user, array $data, ?User $updater = null): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        if (isset($data['roles'])) {
            $user->roles()->sync($data['roles']);
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
