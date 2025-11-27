<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tratamiento extends Model
{
    protected $fillable = [
        'cita_id',
        'descripcion',
        'observaciones',
    ];

    // Relaciones
    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }
}
