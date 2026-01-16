<?php

namespace App\DTOs;

class CreateIndicadorDTO
{
    public function __construct(
        public string $nombre,
        public string $descripcion,
        public string $formula,
        public string $meta,
        public string $unidad,
        public int $responsable_id,
        public ?int $id_norma = null,
        public bool $activo = true
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nombre: $data['nombre'],
            descripcion: $data['descripcion'],
            formula: $data['formula'],
            meta: $data['meta'],
            unidad: $data['unidad'],
            responsable_id: $data['responsable_id'],
            id_norma: $data['id_norma'] ?? null,
            activo: $data['activo'] ?? true
        );
    }
}
