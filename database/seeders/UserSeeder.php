<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        $admin = User::firstOrCreate([
            'correo' => 'admin@nova.com'
        ], [
            'nombre' => 'Administrador Sistema',
            'password' => Hash::make('password'),
            'activo' => true
        ]);

        $adminRole = Rol::where('nombre', 'Administrador')->first();
        if ($adminRole) {
            $admin->roles()->syncWithoutDetaching([$adminRole->id_rol]);
        }

        // Gerente
        $manager = User::firstOrCreate([
            'correo' => 'gerente@nova.com'
        ], [
            'nombre' => 'Gerente General',
            'password' => Hash::make('password'),
            'activo' => true
        ]);

        $managerRole = Rol::where('nombre', 'Gerente')->first();
        if ($managerRole) {
            $manager->roles()->syncWithoutDetaching([$managerRole->id_rol]);
        }

        // Usuario
        $user = User::firstOrCreate([
            'correo' => 'usuario@nova.com'
        ], [
            'nombre' => 'Usuario Invitado',
            'password' => Hash::make('password'),
            'activo' => true
        ]);

        $userRole = Rol::where('nombre', 'Usuario')->first();
        if ($userRole) {
            $user->roles()->syncWithoutDetaching([$userRole->id_rol]);
        }
    }
}
