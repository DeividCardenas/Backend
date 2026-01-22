<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comite;
use App\Models\User;

class ComiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get an existing user (e.g., Gerente) to assign as responsable
        $gerente = User::where('correo', 'gerente@nova.com')->first();

        $responsableId = $gerente?->id_usuario ?? User::factory()->create()->id_usuario;

        Comite::firstOrCreate([
            'nombre' => 'Comité de Calidad'
        ], [
            'objetivo' => 'Supervisar la implementación de ISO 9001',
            'responsable_id' => $responsableId
        ]);

        Comite::firstOrCreate([
            'nombre' => 'Comité de Seguridad'
        ], [
            'objetivo' => 'Gestionar riesgos laborales',
            'responsable_id' => $responsableId
        ]);

        // Generate additional random comites
        Comite::factory(3)->create([
            'responsable_id' => $responsableId
        ]);
    }
}
