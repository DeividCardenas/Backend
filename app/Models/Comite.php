<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comite extends Model
{
    use HasFactory;

    protected $table = 'comites';
    protected $primaryKey = 'id_comite';

    protected $fillable = [
        'nombre',
        'objetivo',
        'responsable_id',
    ];

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id', 'id_usuario');
    }

    public function miembros()
    {
        return $this->belongsToMany(User::class, 'comite_miembros', 'id_comite', 'id_usuario')
                    ->withTimestamps();
    }

    public function reuniones()
    {
        return $this->hasMany(Reunion::class, 'id_comite', 'id_comite');
    }
}
