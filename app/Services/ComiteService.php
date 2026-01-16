<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\CreateComiteDTO;
use App\DTOs\UpdateComiteDTO;
use App\Models\Comite;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Exceptions\ResourceConflictException;

class ComiteService
{
    public function createComite(CreateComiteDTO $dto, ?User $creator = null): Comite
    {
        $comite = Comite::create([
            'nombre' => $dto->nombre,
            'objetivo' => $dto->objetivo,
            'responsable_id' => $dto->responsable_id,
        ]);

        if ($dto->miembros) {
            $comite->miembros()->attach($dto->miembros);
        }

        if ($creator) {
            Log::info('Comité creado por ' . $creator->correo . ': ' . $comite->nombre);
        }

        return $comite;
    }

    public function updateComite(Comite $comite, UpdateComiteDTO $dto, ?User $updater = null): Comite
    {
        $updateData = [];

        if ($dto->isDefined('nombre')) {
            $updateData['nombre'] = $dto->nombre;
        }
        if ($dto->isDefined('objetivo')) {
            $updateData['objetivo'] = $dto->objetivo;
        }
        if ($dto->isDefined('responsable_id')) {
            $updateData['responsable_id'] = $dto->responsable_id;
        }

        $comite->update($updateData);

        if ($dto->isDefined('miembros')) {
            $comite->miembros()->sync($dto->miembros);
        }

        if ($updater) {
            Log::info('Comité actualizado por ' . $updater->correo . ': ' . $comite->nombre);
        }

        return $comite;
    }

    public function deleteComite(Comite $comite, ?User $deleter = null): void
    {
        if ($comite->reuniones()->count() > 0) {
            throw new ResourceConflictException("No se puede eliminar el comité '{$comite->nombre}' porque tiene reuniones registradas.");
        }

        $nombre = $comite->nombre;
        $comite->delete();

        if ($deleter) {
            Log::info('Comité eliminado por ' . $deleter->correo . ': ' . $nombre);
        }
    }
}
