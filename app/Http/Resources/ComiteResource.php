<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComiteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id_comite,
            'nombre' => $this->nombre,
            'objetivo' => $this->objetivo,
            'responsable' => new UserResource($this->whenLoaded('responsable')),
            'miembros' => UserResource::collection($this->whenLoaded('miembros')),
            'reuniones' => ReunionResource::collection($this->whenLoaded('reuniones')),
        ];
    }
}
