<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Odontograma extends Model
{
    use HasFactory;

    protected $table = 'odontograma';

    protected $fillable = [
        'paciente_id',
        'pieza_dental',
        'estado',
        'observaciones',
        'fecha_registro',
    ];

    protected $casts = [
        'pieza_dental' => 'integer',
        'fecha_registro' => 'date',
    ];

    // Relaciones
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}