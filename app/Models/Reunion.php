<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reunion extends Model
{
    protected $table = 'reuniones';
    protected $primaryKey = 'id_reunion';

    protected $fillable = [
        'id_comite',
        'fecha',
        'tema',
        'acuerdos',
        'archivo_acta',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
        ];
    }

    public function comite()
    {
        return $this->belongsTo(Comite::class, 'id_comite', 'id_comite');
    }
}
