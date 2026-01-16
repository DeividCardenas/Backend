<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\CreateIndicadorDTO;
use App\DTOs\UpdateIndicadorDTO;
use App\Models\Indicador;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class IndicadorService
{
    public function createIndicador(CreateIndicadorDTO $dto, ?User $creator = null): Indicador
    {
        $indicador = Indicador::create([
            'nombre' => $dto->nombre,
            'descripcion' => $dto->descripcion,
            'formula' => $dto->formula,
            'meta' => $dto->meta,
            'unidad' => $dto->unidad,
            'responsable_id' => $dto->responsable_id,
            'id_norma' => $dto->id_norma,
            'activo' => $dto->activo,
        ]);

        if ($creator) {
            Log::info('Indicador creado por ' . $creator->correo . ': ' . $indicador->nombre);
        }

        return $indicador;
    }

    public function updateIndicador(Indicador $indicador, UpdateIndicadorDTO $dto, ?User $updater = null): Indicador
    {
        $updateData = [];

        if ($dto->isDefined('nombre')) {
            $updateData['nombre'] = $dto->nombre;
        }
        if ($dto->isDefined('descripcion')) {
            $updateData['descripcion'] = $dto->descripcion;
        }
        if ($dto->isDefined('formula')) {
            $updateData['formula'] = $dto->formula;
        }
        if ($dto->isDefined('meta')) {
            $updateData['meta'] = $dto->meta;
        }
        if ($dto->isDefined('unidad')) {
            $updateData['unidad'] = $dto->unidad;
        }
        if ($dto->isDefined('responsable_id')) {
            $updateData['responsable_id'] = $dto->responsable_id;
        }
        if ($dto->isDefined('id_norma')) {
            $updateData['id_norma'] = $dto->id_norma;
        }
        if ($dto->isDefined('activo')) {
            $updateData['activo'] = $dto->activo;
        }

        $indicador->update($updateData);

        if ($updater) {
            Log::info('Indicador actualizado por ' . $updater->correo . ': ' . $indicador->nombre);
        }

        return $indicador;
    }

    public function deactivateIndicador(Indicador $indicador, ?User $deactivator = null): void
    {
        $indicador->update(['activo' => false]);

        if ($deactivator) {
            Log::info('Indicador desactivado por ' . $deactivator->correo . ': ' . $indicador->nombre);
        }
    }
}
