<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndicadorValorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id_valor,
            'valor' => $this->valor,
            'fecha' => $this->fecha->format('Y-m-d'),
            'observaciones' => $this->observaciones,
            'indicador' => new IndicadorResource($this->whenLoaded('indicador')),
            'registrado_por' => new UserResource($this->whenLoaded('registradoPor')),
        ];
    }
}
