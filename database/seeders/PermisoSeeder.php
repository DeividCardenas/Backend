<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permiso;

class PermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entities = [
            'usuarios',
            'roles',
            'permisos',
            'comites',
            'reuniones',
            'indicadores',
            'valores'
        ];

        $actions = [
            'ver' => 'Ver',
            'crear' => 'Crear',
            'editar' => 'Editar',
            'eliminar' => 'Eliminar'
        ];

        foreach ($entities as $entity) {
            foreach ($actions as $action => $label) {
                Permiso::firstOrCreate([
                    'nombre' => "{$action}_{$entity}"
                ], [
                    'descripcion' => "Permite {$label} {$entity}"
                ]);
            }
        }
    }
}
