<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    protected $table = 'historiales';

    protected $fillable = [
        'paciente_id',
        'diagnostico',
        'fecha_apertura',
        'observacion',
    ];

    protected $casts = [
        'fecha_apertura' => 'date',
    ];

    // Relaciones
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    public function ultimaCita()
    {
        return $this->citas()->latest('fecha')->first();
    }

    public function citasCompletadas()
    {
        return $this->citas()->where('estado', 'completada')->get();
    }
}