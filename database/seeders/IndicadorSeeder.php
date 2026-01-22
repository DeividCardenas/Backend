<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Indicador;
use App\Models\User;

class IndicadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gerente = User::where('correo', 'gerente@nova.com')->first();
        $responsableId = $gerente?->id_usuario ?? User::factory()->create()->id_usuario;

        Indicador::firstOrCreate([
            'nombre' => 'Satisfacción del Cliente'
        ], [
            'descripcion' => 'Porcentaje de clientes satisfechos encuestados',
            'formula' => '(Clientes Satisfechos / Total Encuestados) * 100',
            'meta' => '90',
            'unidad' => '%',
            'responsable_id' => $responsableId,
            'id_norma' => null,
            'activo' => true
        ]);

        Indicador::factory(5)->create([
            'responsable_id' => $responsableId
        ]);
    }
}
