<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id_usuario,
            'nombre' => $this->nombre,
            'correo' => $this->correo,
            'activo' => (bool) $this->activo,
            'roles' => RolResource::collection($this->whenLoaded('roles')),
            // 'comites_responsable' => ComiteResource::collection($this->whenLoaded('comitesResponsable')), // Will add later if needed, but creates circular dependency if ComiteResource includes UserResource.
            // Usually we handle circular dependency by not including it or using a simplified resource.
            // For now, I'll stick to Roles.
        ];
    }
}
