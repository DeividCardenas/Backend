<?php

namespace App\DTOs;

class UpdateIndicadorDTO
{
    private array $definedFields = [];

    public function __construct(
        public ?string $nombre = null,
        public ?string $descripcion = null,
        public ?string $formula = null,
        public ?string $meta = null,
        public ?string $unidad = null,
        public ?int $responsable_id = null,
        public ?int $id_norma = null,
        public ?bool $activo = null
    ) {}

    public static function fromArray(array $data): self
    {
        $dto = new self(
            nombre: $data['nombre'] ?? null,
            descripcion: $data['descripcion'] ?? null,
            formula: $data['formula'] ?? null,
            meta: $data['meta'] ?? null,
            unidad: $data['unidad'] ?? null,
            responsable_id: $data['responsable_id'] ?? null,
            id_norma: $data['id_norma'] ?? null,
            activo: isset($data['activo']) ? (bool) $data['activo'] : null
        );

        $dto->setDefinedFields($data);
        return $dto;
    }

    private function setDefinedFields(array $data): void
    {
        $this->definedFields = array_keys($data);
    }

    public function isDefined(string $field): bool
    {
        return in_array($field, $this->definedFields);
    }
}
