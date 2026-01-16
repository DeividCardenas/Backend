<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReunionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id_reunion,
            'fecha' => $this->fecha->format('Y-m-d'),
            'tema' => $this->tema,
            'acuerdos' => $this->acuerdos,
            'archivo_acta' => $this->archivo_acta, // You might want to wrap this in a URL accessor in the model or here
            'comite' => new ComiteResource($this->whenLoaded('comite')),
        ];
    }
}
