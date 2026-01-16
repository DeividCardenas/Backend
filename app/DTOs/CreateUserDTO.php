<?php

namespace App\DTOs;

class CreateUserDTO
{
    public function __construct(
        public string $nombre,
        public string $correo,
        public string $password,
        public bool $activo = true,
        public ?array $roles = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nombre: $data['nombre'],
            correo: $data['correo'],
            password: $data['password'],
            activo: $data['activo'] ?? true,
            roles: $data['roles'] ?? null
        );
    }
}
