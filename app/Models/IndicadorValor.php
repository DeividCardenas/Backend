<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndicadorValor extends Model
{
    protected $table = 'indicador_valores';
    protected $primaryKey = 'id_valor';

    protected $fillable = [
        'id_indicador',
        'valor',
        'fecha',
        'observaciones',
        'registrado_por',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'valor' => 'decimal:2',
        ];
    }

    public function indicador()
    {
        return $this->belongsTo(Indicador::class, 'id_indicador', 'id_indicador');
    }

    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por', 'id_usuario');
    }
}
