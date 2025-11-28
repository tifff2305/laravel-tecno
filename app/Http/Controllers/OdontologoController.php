<?php

namespace App\Http\Controllers;

use App\Models\Odontologo;
use Illuminate\Http\Request;

class OdontologoController extends Controller
{
    public function index()
    {
        $odontologos = Odontologo::with('user')->get();
        return response()->json($odontologos);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'especialidad' => 'required|string|max:255',
            'activo' => 'boolean',
        ]);

        $odontologo = Odontologo::create($validated);
        return response()->json($odontologo, 201);
    }

    public function show(Odontologo $odontologo)
    {
        return response()->json($odontologo->load('user'));
    }

    public function update(Request $request, Odontologo $odontologo)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'especialidad' => 'required|string|max:255',
            'activo' => 'boolean',
        ]);

        $odontologo->update($validated);
        return response()->json($odontologo);
    }

    public function destroy(Odontologo $odontologo)
    {
        $odontologo->delete();
        return response()->json(null, 204);
    }
}