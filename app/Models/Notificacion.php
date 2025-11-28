<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'notificaciones';

    protected $fillable = [
        'cita_id',
        'tipo',
        'canal',
        'estado',
        'fecha_envio',
    ];

    protected $casts = [
        'fecha_envio' => 'datetime',
        'created_at' => 'datetime',
    ];

    // Relaciones
    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }
}