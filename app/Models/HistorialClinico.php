<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialClinico extends Model
{
    use HasFactory;

    protected $table = 'historial_clinico';

    protected $fillable = [
        'paciente_id',
        'antecedentes_medicos',
        'alergias',
        'observaciones',
        'fecha_apertura',
    ];

    protected $casts = [
        'fecha_apertura' => 'date',
    ];

    // Relaciones
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}