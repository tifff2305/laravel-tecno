<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $fillable = [
        'cita_id',
        'paciente_id',
        'monto_total',
        'monto_pagado',
        'saldo_pendiente',
        'forma_pago',
        'estado',
        'fecha_emision',
    ];

    protected $casts = [
        'monto_total' => 'decimal:2',
        'monto_pagado' => 'decimal:2',
        'saldo_pendiente' => 'decimal:2',
        'fecha_emision' => 'date',
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

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
}