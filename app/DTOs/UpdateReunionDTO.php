<?php

namespace App\DTOs;

use Illuminate\Http\UploadedFile;

class UpdateReunionDTO
{
    private array $definedFields = [];

    public function __construct(
        public ?int $id_comite = null,
        public ?string $fecha = null,
        public ?string $tema = null,
        public ?string $acuerdos = null,
        public ?UploadedFile $archivo_acta = null
    ) {}

    public static function fromArray(array $data, ?UploadedFile $archivo_acta = null): self
    {
        $dto = new self(
            id_comite: $data['id_comite'] ?? null,
            fecha: $data['fecha'] ?? null,
            tema: $data['tema'] ?? null,
            acuerdos: $data['acuerdos'] ?? null,
            archivo_acta: $archivo_acta
        );

        // Treat file separately or include in defined fields
        $defined = array_keys($data);
        if ($archivo_acta) {
            $defined[] = 'archivo_acta';
        }

        $dto->setDefinedFields($defined);
        return $dto;
    }

    private function setDefinedFields(array $fields): void
    {
        $this->definedFields = $fields;
    }

    public function isDefined(string $field): bool
    {
        return in_array($field, $this->definedFields);
    }
}
