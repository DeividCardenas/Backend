<?php

namespace App\Services;

use App\Models\Comite;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ComiteService
{
    public function createComite(array $data, ?User $creator = null): Comite
    {
        $comite = Comite::create([
            'nombre' => $data['nombre'],
            'objetivo' => $data['objetivo'],
            'responsable_id' => $data['responsable_id'] ?? null,
        ]);

        if (isset($data['miembros'])) {
            $comite->miembros()->attach($data['miembros']);
        }

        if ($creator) {
            Log::info('Comité creado por ' . $creator->correo . ': ' . $comite->nombre);
        }

        return $comite;
    }

    public function updateComite(Comite $comite, array $data, ?User $updater = null): Comite
    {
        $comite->update($data);

        if (isset($data['miembros'])) {
            $comite->miembros()->sync($data['miembros']);
        }

        if ($updater) {
            Log::info('Comité actualizado por ' . $updater->correo . ': ' . $comite->nombre);
        }

        return $comite;
    }

    public function deleteComite(Comite $comite, ?User $deleter = null): void
    {
        $nombre = $comite->nombre;
        $comite->delete();

        if ($deleter) {
            Log::info('Comité eliminado por ' . $deleter->correo . ': ' . $nombre);
        }
    }
}
