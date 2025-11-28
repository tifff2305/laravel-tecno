<?php

namespace App\Http\Controllers;

use App\Models\Tratamiento;
use Illuminate\Http\Request;

class TratamientoController extends Controller
{
    public function index()
    {
        $tratamientos = Tratamiento::with(['cita', 'paciente', 'odontologo'])->get();
        return response()->json($tratamientos);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cita_id' => 'required|exists:citas,id',
            'paciente_id' => 'required|exists:pacientes,id',
            'odontologo_id' => 'required|exists:odontologos,id',
            'diagnostico' => 'required|string',
            'plan_tratamiento' => 'required|string',
            'estado' => 'nullable|in:planificado,en_proceso,completado',
            'fecha_inicio' => 'required|date',
            'observaciones' => 'nullable|string',
        ]);

        $tratamiento = Tratamiento::create($validated);
        return response()->json($tratamiento, 201);
    }

    public function show(Tratamiento $tratamiento)
    {
        return response()->json($tratamiento->load(['cita', 'paciente', 'odontologo']));
    }

    public function update(Request $request, Tratamiento $tratamiento)
    {
        $validated = $request->validate([
            'cita_id' => 'required|exists:citas,id',
            'paciente_id' => 'required|exists:pacientes,id',
            'odontologo_id' => 'required|exists:odontologos,id',
            'diagnostico' => 'required|string',
            'plan_tratamiento' => 'required|string',
            'estado' => 'nullable|in:planificado,en_proceso,completado',
            'fecha_inicio' => 'required|date',
            'observaciones' => 'nullable|string',
        ]);

        $tratamiento->update($validated);
        return response()->json($tratamiento);
    }

    public function destroy(Tratamiento $tratamiento)
    {
        $tratamiento->delete();
        return response()->json(null, 204);
    }

    // Método adicional para obtener tratamientos por paciente
    public function showByPaciente($paciente_id)
    {
        $tratamientos = Tratamiento::where('paciente_id', $paciente_id)
                                   ->with(['cita', 'odontologo'])
                                   ->get();
        return response()->json($tratamientos);
    }

    // Método adicional para obtener tratamientos por estado
    public function showByEstado($estado)
    {
        $tratamientos = Tratamiento::where('estado', $estado)
                                   ->with(['cita', 'paciente', 'odontologo'])
                                   ->get();
        return response()->json($tratamientos);
    }
}