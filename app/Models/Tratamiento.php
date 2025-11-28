<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tratamiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'cita_id',
        'paciente_id',
        'odontologo_id',
        'diagnostico',
        'plan_tratamiento',
        'estado',
        'fecha_inicio',
        'observaciones',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
    ];

    // Relaciones
    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function odontologo()
    {
        return $this->belongsTo(Odontologo::class);
    }
}