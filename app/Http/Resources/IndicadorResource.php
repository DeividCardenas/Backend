<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndicadorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id_indicador,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'formula' => $this->formula,
            'meta' => $this->meta,
            'unidad' => $this->unidad,
            'activo' => (bool) $this->activo,
            'responsable' => new UserResource($this->whenLoaded('responsable')),
            'valores' => IndicadorValorResource::collection($this->whenLoaded('valores')),
        ];
    }
}
