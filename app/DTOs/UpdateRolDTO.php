<?php

namespace App\DTOs;

class UpdateRolDTO
{
    private array $definedFields = [];

    public function __construct(
        public ?string $nombre = null,
        public ?string $descripcion = null,
        public ?array $permisos = null
    ) {}

    public static function fromArray(array $data): self
    {
        $dto = new self(
            nombre: $data['nombre'] ?? null,
            descripcion: $data['descripcion'] ?? null,
            permisos: $data['permisos'] ?? null
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
