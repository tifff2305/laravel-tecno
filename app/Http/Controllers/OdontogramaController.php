<?php

namespace App\Http\Controllers;

use App\Models\Odontograma;
use Illuminate\Http\Request;

class OdontogramaController extends Controller
{
    public function index()
    {
        $odontogramas = Odontograma::with('paciente')->get();
        return response()->json($odontogramas);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'pieza_dental' => 'required|integer|min:1|max:32',
            'estado' => 'required|in:sano,caries,obturacion,corona,ausente,otro',
            'observaciones' => 'nullable|string',
            'fecha_registro' => 'required|date',
        ]);

        $odontograma = Odontograma::create($validated);
        return response()->json($odontograma, 201);
    }

    public function show(Odontograma $odontograma)
    {
        return response()->json($odontograma->load('paciente'));
    }

    public function update(Request $request, Odontograma $odontograma)
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'pieza_dental' => 'required|integer|min:1|max:32',
            'estado' => 'required|in:sano,caries,obturacion,corona,ausente,otro',
            'observaciones' => 'nullable|string',
            'fecha_registro' => 'required|date',
        ]);

        $odontograma->update($validated);
        return response()->json($odontograma);
    }

    public function destroy(Odontograma $odontograma)
    {
        $odontograma->delete();
        return response()->json(null, 204);
    }

    // Método adicional para obtener odontograma completo de un paciente
    public function showByPaciente($paciente_id)
    {
        $odontogramas = Odontograma::where('paciente_id', $paciente_id)->get();
        return response()->json($odontogramas);
    }
}