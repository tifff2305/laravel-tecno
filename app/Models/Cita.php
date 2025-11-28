<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $fillable = [
        'paciente_id',
        'odontologo_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'estado',
        'motivo_consulta',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
    ];

    // Relaciones
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function odontologo()
    {
        return $this->belongsTo(Odontologo::class);
    }

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'cita_servicio')
                    ->withPivot('precio_aplicado')
                    ->withTimestamps();
    }

    public function tratamiento()
    {
        return $this->hasOne(Tratamiento::class);
    }

    public function factura()
    {
        return $this->hasOne(Factura::class);
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class);
    }
}