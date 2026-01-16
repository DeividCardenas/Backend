<?php

namespace App\DTOs;

class CreateRolDTO
{
    public function __construct(
        public string $nombre,
        public ?string $descripcion = null,
        public ?array $permisos = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nombre: $data['nombre'],
            descripcion: $data['descripcion'] ?? null,
            permisos: $data['permisos'] ?? null
        );
    }
}
