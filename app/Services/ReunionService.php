<?php

namespace App\Services;

use App\DTOs\CreateReunionDTO;
use App\DTOs\UpdateReunionDTO;
use App\Models\Reunion;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ReunionService
{
    public function createReunion(CreateReunionDTO $dto): Reunion
    {
        $data = [
            'id_comite' => $dto->id_comite,
            'fecha' => $dto->fecha,
            'tema' => $dto->tema,
            'acuerdos' => $dto->acuerdos,
        ];

        if ($dto->archivo_acta) {
            $data['archivo_acta'] = $dto->archivo_acta->store('actas', 'public');
        }

        return Reunion::create($data);
    }

    public function updateReunion(Reunion $reunion, UpdateReunionDTO $dto): Reunion
    {
        $data = [];
        if ($dto->isDefined('id_comite')) $data['id_comite'] = $dto->id_comite;
        if ($dto->isDefined('fecha')) $data['fecha'] = $dto->fecha;
        if ($dto->isDefined('tema')) $data['tema'] = $dto->tema;
        if ($dto->isDefined('acuerdos')) $data['acuerdos'] = $dto->acuerdos;

        if ($dto->isDefined('archivo_acta')) {
            if ($reunion->archivo_acta) {
                Storage::disk('public')->delete($reunion->archivo_acta);
            }
            if ($dto->archivo_acta) {
                $data['archivo_acta'] = $dto->archivo_acta->store('actas', 'public');
            } else {
                 // If null is sent, do we delete the file? Assuming yes if it is 'defined' as null.
                 $data['archivo_acta'] = null;
            }
        }

        $reunion->update($data);

        return $reunion;
    }

    public function deleteReunion(Reunion $reunion): void
    {
        if ($reunion->archivo_acta) {
            Storage::disk('public')->delete($reunion->archivo_acta);
        }

        $reunion->delete();
    }
}
