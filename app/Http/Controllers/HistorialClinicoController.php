<?php

namespace App\Http\Controllers;

use App\Models\HistorialClinico;
use Illuminate\Http\Request;

class HistorialClinicoController extends Controller
{
    public function index()
    {
        $historiales = HistorialClinico::with('paciente')->get();
        return response()->json($historiales);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id|unique:historial_clinico,paciente_id',
            'antecedentes_medicos' => 'nullable|string',
            'alergias' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'fecha_apertura' => 'required|date',
        ]);

        $historial = HistorialClinico::create($validated);
        return response()->json($historial, 201);
    }

    public function show(HistorialClinico $historialClinico)
    {
        return response()->json($historialClinico->load('paciente'));
    }

    public function update(Request $request, HistorialClinico $historialClinico)
    {
        $validated = $request->validate([
            'antecedentes_medicos' => 'nullable|string',
            'alergias' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'fecha_apertura' => 'required|date',
        ]);

        $historialClinico->update($validated);
        return response()->json($historialClinico);
    }

    public function destroy(HistorialClinico $historialClinico)
    {
        $historialClinico->delete();
        return response()->json(null, 204);
    }

    // Método adicional para obtener historial por paciente
    public function showByPaciente($paciente_id)
    {
        $historial = HistorialClinico::where('paciente_id', $paciente_id)->first();
        
        if (!$historial) {
            return response()->json(['message' => 'Historial no encontrado'], 404);
        }

        return response()->json($historial);
    }
}