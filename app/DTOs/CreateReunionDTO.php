<?php

namespace App\DTOs;

use Illuminate\Http\UploadedFile;

class CreateReunionDTO
{
    public function __construct(
        public int $id_comite,
        public string $fecha,
        public string $tema,
        public string $acuerdos,
        public ?UploadedFile $archivo_acta = null
    ) {}

    public static function fromArray(array $data, ?UploadedFile $archivo_acta = null): self
    {
        return new self(
            id_comite: $data['id_comite'],
            fecha: $data['fecha'],
            tema: $data['tema'],
            acuerdos: $data['acuerdos'],
            archivo_acta: $archivo_acta
        );
    }
}
