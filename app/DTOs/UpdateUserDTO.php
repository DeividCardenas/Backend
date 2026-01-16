<?php

namespace App\DTOs;

class UpdateUserDTO
{
    private array $definedFields = [];

    public function __construct(
        public ?string $nombre = null,
        public ?string $correo = null,
        public ?string $password = null,
        public ?bool $activo = null,
        public ?array $roles = null
    ) {}

    public static function fromArray(array $data): self
    {
        $dto = new self(
            nombre: $data['nombre'] ?? null,
            correo: $data['correo'] ?? null,
            password: $data['password'] ?? null,
            activo: isset($data['activo']) ? (bool) $data['activo'] : null,
            roles: $data['roles'] ?? null
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
