<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = [
        'cita_id',
        'paciente_id',
        'monto_total',
        'monto_pagado',
        'saldo_pendiente',
        'metodo_de_pago',
        'estado',
        'fecha_pago',
    ];

    protected $casts = [
        'monto_total' => 'decimal:2',
        'monto_pagado' => 'decimal:2',
        'saldo_pendiente' => 'decimal:2',
        'fecha_pago' => 'date',
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

    public function cuotas()
    {
        return $this->hasMany(Cuota::class);
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->whereIn('estado', ['pendiente', 'parcial']);
    }
}