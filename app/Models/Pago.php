<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'cuota_id',
        'factura_id',
        'monto_pagado',
        'metodo_pago',
        'fecha_pago',
    ];

    protected $casts = [
        'monto_pagado' => 'decimal:2',
        'fecha_pago' => 'date',
        'created_at' => 'datetime',
    ];

    // Relaciones
    public function cuota()
    {
        return $this->belongsTo(Cuota::class);
    }

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }
}