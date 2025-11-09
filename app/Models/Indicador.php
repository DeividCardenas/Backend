<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indicador extends Model
{
    protected $table = 'indicadores';
    protected $primaryKey = 'id_indicador';

    protected $fillable = [
        'nombre',
        'descripcion',
        'formula',
        'meta',
        'unidad',
        'responsable_id',
        'id_norma',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id', 'id_usuario');
    }

    public function valores()
    {
        return $this->hasMany(IndicadorValor::class, 'id_indicador', 'id_indicador');
    }
}
