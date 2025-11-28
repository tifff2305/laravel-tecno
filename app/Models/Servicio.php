<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servicio extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'duracion_minutos',
        'activo',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'duracion_minutos' => 'integer',
        'activo' => 'boolean',
    ];

    // Relaciones
    public function citas()
    {
        return $this->belongsToMany(Cita::class, 'cita_servicio')
                    ->withPivot('precio_aplicado')
                    ->withTimestamps();
    }
}