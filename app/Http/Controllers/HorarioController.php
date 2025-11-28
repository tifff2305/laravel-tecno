<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    public function index()
    {
        $horarios = Horario::with('odontologo')->get();
        return response()->json($horarios);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'odontologo_id' => 'required|exists:odontologos,id',
            'dia_semana' => 'required|integer|min:0|max:6',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'activo' => 'boolean',
        ]);

        $horario = Horario::create($validated);
        return response()->json($horario, 201);
    }

    public function show(Horario $horario)
    {
        return response()->json($horario->load('odontologo'));
    }

    public function update(Request $request, Horario $horario)
    {
        $validated = $request->validate([
            'odontologo_id' => 'required|exists:odontologos,id',
            'dia_semana' => 'required|integer|min:0|max:6',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'activo' => 'boolean',
        ]);

        $horario->update($validated);
        return response()->json($horario);
    }

    public function destroy(Horario $horario)
    {
        $horario->delete();
        return response()->json(null, 204);
    }
}