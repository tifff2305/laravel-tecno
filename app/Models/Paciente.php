<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Paciente extends Model
{
    protected $fillable = [
        //'user_id',
        'nombre',
        'apellido',
        'telefono',
    ];

    // Relaciones
    /* public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    } */

    public function historial()
    {
        return $this->hasOne(Historial::class);
    }

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    // Accessors
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }

    // Scopes
    public function scopeBuscar($query, $termino)
    {
        return $query->where(function($q) use ($termino) {
            $q->where('nombre', 'like', "%{$termino}%")
              ->orWhere('apellido', 'like', "%{$termino}%")
              ->orWhere('ci', 'like', "%{$termino}%");
        });
    }
}