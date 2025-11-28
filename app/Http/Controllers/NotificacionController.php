<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function index()
    {
        $notificaciones = Notificacion::with('cita')->get();
        return response()->json($notificaciones);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cita_id' => 'required|exists:citas,id',
            'tipo' => 'required|in:recordatorio,confirmacion',
            'canal' => 'required|in:email,whatsapp',
            'estado' => 'nullable|in:pendiente,enviado',
        ]);

        $notificacion = Notificacion::create($validated);
        return response()->json($notificacion, 201);
    }

    public function show(Notificacion $notificacion)
    {
        return response()->json($notificacion->load('cita'));
    }

    public function update(Request $request, Notificacion $notificacion)
    {
        $validated = $request->validate([
            'estado' => 'required|in:pendiente,enviado',
            'fecha_envio' => 'nullable|date',
        ]);

        $notificacion->update($validated);
        return response()->json($notificacion);
    }

    public function destroy(Notificacion $notificacion)
    {
        $notificacion->delete();
        return response()->json(null, 204);
    }

    // Método adicional para obtener notificaciones pendientes
    public function showPendientes()
    {
        $notificaciones = Notificacion::where('estado', 'pendiente')
                                     ->with('cita.paciente')
                                     ->get();
        return response()->json($notificaciones);
    }

    // Método adicional para marcar como enviada
    public function marcarEnviada(Notificacion $notificacion)
    {
        $notificacion->update([
            'estado' => 'enviado',
            'fecha_envio' => now(),
        ]);
        
        return response()->json($notificacion);
    }
}