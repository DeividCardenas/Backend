<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;
use App\Models\Permiso;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Administrador
        $adminRole = Rol::firstOrCreate([
            'nombre' => 'Administrador'
        ], [
            'descripcion' => 'Acceso total al sistema'
        ]);

        $allPermissions = Permiso::all();
        $adminRole->permisos()->sync($allPermissions);

        // Gerente
        $managerRole = Rol::firstOrCreate([
            'nombre' => 'Gerente'
        ], [
            'descripcion' => 'Gestión de comités e indicadores'
        ]);

        $managerPermissions = Permiso::where('nombre', 'not like', '%usuarios%')
            ->where('nombre', 'not like', '%roles%')
            ->where('nombre', 'not like', '%permisos%')
            ->get();

        $managerRole->permisos()->sync($managerPermissions);

        // Usuario Básico
        $userRole = Rol::firstOrCreate([
            'nombre' => 'Usuario'
        ], [
            'descripcion' => 'Consulta de información'
        ]);

        $userPermissions = Permiso::where('nombre', 'like', 'ver_%')->get();
        $userRole->permisos()->sync($userPermissions);
    }
}
