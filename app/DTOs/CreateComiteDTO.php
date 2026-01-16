<?php

namespace App\DTOs;

class CreateComiteDTO
{
    public function __construct(
        public string $nombre,
        public string $objetivo,
        public ?int $responsable_id = null,
        public ?array $miembros = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nombre: $data['nombre'],
            objetivo: $data['objetivo'],
            responsable_id: $data['responsable_id'] ?? null,
            miembros: $data['miembros'] ?? null
        );
    }
}
