<?php

namespace App\Http\Controllers;

use App\Models\Secretaria;
use Illuminate\Http\Request;

class SecretariaController extends Controller
{
    public function index()
    {
        $secretarias = Secretaria::with('user')->get();
        return response()->json($secretarias);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'activo' => 'boolean',
        ]);

        $secretaria = Secretaria::create($validated);
        return response()->json($secretaria, 201);
    }

    public function show(Secretaria $secretaria)
    {
        return response()->json($secretaria->load('user'));
    }

    public function update(Request $request, Secretaria $secretaria)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'activo' => 'boolean',
        ]);

        $secretaria->update($validated);
        return response()->json($secretaria);
    }

    public function destroy(Secretaria $secretaria)
    {
        $secretaria->delete();
        return response()->json(null, 204);
    }
}