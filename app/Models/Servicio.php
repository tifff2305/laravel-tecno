<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $fillable = [
        'nombre',
        'precio',
        'estado',
        'descripcion',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
    ];

    // Relaciones
    public function citas()
    {
        return $this->belongsToMany(Cita::class, 'cita_servicio')
                    ->withPivot('precio', 'cantidad')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    // Accessors
    public function getPrecioFormateadoAttribute()
    {
        return 'Bs. ' . number_format($this->precio, 2);
    }
}