<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table = 'horarios';

    protected $fillable = [
        'medico_id',
        'dia',
        'hora_inicio',
        'hora_fin',
        'descripcion',
    ];

    // Relaciones
    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }

}
