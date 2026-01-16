<?php

namespace App\Services;

use App\Models\Indicador;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class IndicadorService
{
    public function createIndicador(array $data, ?User $creator = null): Indicador
    {
        $indicador = Indicador::create($data);

        if ($creator) {
            Log::info('Indicador creado por ' . $creator->correo . ': ' . $indicador->nombre);
        }

        return $indicador;
    }

    public function updateIndicador(Indicador $indicador, array $data, ?User $updater = null): Indicador
    {
        $indicador->update($data);

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
