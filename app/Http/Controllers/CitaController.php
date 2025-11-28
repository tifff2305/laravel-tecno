<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    public function index()
    {
        $citas = Cita::with(['paciente', 'odontologo', 'servicios'])->get();
        return response()->json($citas);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'odontologo_id' => 'required|exists:odontologos,id',
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'estado' => 'nullable|in:pendiente,confirmada,completada,cancelada',
            'motivo_consulta' => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        $cita = Cita::create($validated);
        return response()->json($cita, 201);
    }

    public function show(Cita $cita)
    {
        return response()->json($cita->load(['paciente', 'odontologo', 'servicios']));
    }

    public function update(Request $request, Cita $cita)
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'odontologo_id' => 'required|exists:odontologos,id',
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'estado' => 'nullable|in:pendiente,confirmada,completada,cancelada',
            'motivo_consulta' => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        $cita->update($validated);
        return response()->json($cita);
    }

    public function destroy(Cita $cita)
    {
        $cita->delete();
        return response()->json(null, 204);
    }

    // Método adicional para agregar servicios a una cita
    public function attachServicio(Request $request, Cita $cita)
    {
        $validated = $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'precio_aplicado' => 'required|numeric|min:0',
        ]);

        $cita->servicios()->attach($validated['servicio_id'], [
            'precio_aplicado' => $validated['precio_aplicado']
        ]);

        return response()->json($cita->load('servicios'));
    }
}