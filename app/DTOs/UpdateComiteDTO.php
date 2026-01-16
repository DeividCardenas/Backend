<?php

namespace App\DTOs;

class UpdateComiteDTO
{
    private array $definedFields = [];

    public function __construct(
        public ?string $nombre = null,
        public ?string $objetivo = null,
        public ?int $responsable_id = null,
        public ?array $miembros = null
    ) {}

    public static function fromArray(array $data): self
    {
        $dto = new self(
            nombre: $data['nombre'] ?? null,
            objetivo: $data['objetivo'] ?? null,
            responsable_id: $data['responsable_id'] ?? null,
            miembros: $data['miembros'] ?? null
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
