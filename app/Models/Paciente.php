<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paciente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nombre',
        'apellido',
        'telefono',
        'fecha_nacimiento',
        'activo',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'activo' => 'boolean',
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    public function historialClinico()
    {
        return $this->hasOne(HistorialClinico::class);
    }

    public function odontogramas()
    {
        return $this->hasMany(Odontograma::class);
    }

    public function tratamientos()
    {
        return $this->hasMany(Tratamiento::class);
    }

    public function facturas()
    {
        return $this->hasMany(Factura::class);
    }
}