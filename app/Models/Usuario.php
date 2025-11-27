<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'correo',
        'contrasena',
    ];

    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    // Para que Laravel use 'contrasena' en lugar de 'password'
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    // Relaciones
    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'usuario_rol', 'user_id', 'rol_id')
                    ->withTimestamps();
    }

    public function medico()
    {
        return $this->hasOne(Medico::class, 'user_id');
    }

    /* public function paciente()
    {
        return $this->hasOne(Paciente::class, 'user_id');
    } */

    public function secretaria()
    {
        return $this->hasOne(Secretaria::class, 'user_id');
    }

    // Helper methods
    public function hasRole($roleName)
    {
        return $this->roles()->where('nombre', $roleName)->exists();
    }

    public function isMedico()
    {
        return $this->hasRole('medico');
    }

    /* public function isPaciente()
    {
        return $this->hasRole('paciente');
    } */

    public function isSecretaria()
    {
        return $this->hasRole('secretaria');
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /* public function isPropietario()
    {
        return $this->hasRole('propietario');
    } */
}

