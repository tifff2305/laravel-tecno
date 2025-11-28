<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    use HasFactory;

    protected $fillable = [
        'factura_id',
        'numero_cuota',
        'monto',
        'fecha_vencimiento',
        'estado',
    ];

    protected $casts = [
        'numero_cuota' => 'integer',
        'monto' => 'decimal:2',
        'fecha_vencimiento' => 'date',
    ];

    // Relaciones
    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
}