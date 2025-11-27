<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $fillable = [
        'medico_id',
        'paciente_id',
        'historial_id',
        'fecha',
        'estado',
        'motivo_consulta',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Relaciones
    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function historial()
    {
        return $this->belongsTo(Historial::class);
    }

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'cita_servicio')
                    ->withPivot('precio', 'cantidad')
                    ->withTimestamps();
    }

    public function tratamientos()
    {
        return $this->hasMany(Tratamiento::class);
    }

    public function pago()
    {
        return $this->hasOne(Pago::class);
    }

    // Scopes
    public function scopeDelDia($query, $fecha = null)
    {
        $fecha = $fecha ?? today();
        return $query->whereDate('fecha', $fecha);
    }

    public function scopePendientes($query)
    {
        return $query->whereIn('estado', ['pendiente', 'confirmada']);
    }

    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completada');
    }
}
