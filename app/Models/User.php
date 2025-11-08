<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'nombre',
        'correo',
        'password',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    public function getAuthIdentifierName()
    {
        return 'id_usuario';
    }

    public function getEmailForPasswordReset()
    {
        return $this->correo;
    }

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'usuario_rol', 'id_usuario', 'id_rol');
    }

    public function comitesResponsable()
    {
        return $this->hasMany(Comite::class, 'responsable_id', 'id_usuario');
    }

    public function comitesMiembro()
    {
        return $this->belongsToMany(Comite::class, 'comite_miembros', 'id_usuario', 'id_comite');
    }

    public function indicadoresResponsable()
    {
        return $this->hasMany(Indicador::class, 'responsable_id', 'id_usuario');
    }
}
