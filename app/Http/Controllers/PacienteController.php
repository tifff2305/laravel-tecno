<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function index()
    {
        $pacientes = Paciente::with('user')->get();
        return response()->json($pacientes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'fecha_nacimiento' => 'required|date',
            'activo' => 'boolean',
        ]);

        $paciente = Paciente::create($validated);
        return response()->json($paciente, 201);
    }

    public function show(Paciente $paciente)
    {
        return response()->json($paciente->load('user'));
    }

    public function update(Request $request, Paciente $paciente)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'fecha_nacimiento' => 'required|date',
            'activo' => 'boolean',
        ]);

        $paciente->update($validated);
        return response()->json($paciente);
    }

    public function destroy(Paciente $paciente)
    {
        $paciente->delete();
        return response()->json(null, 204);
    }
}