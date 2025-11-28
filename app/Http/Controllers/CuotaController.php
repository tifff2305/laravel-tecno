<?php

namespace App\Http\Controllers;

use App\Models\Cuota;
use Illuminate\Http\Request;

class CuotaController extends Controller
{
    public function index()
    {
        $cuotas = Cuota::with(['factura', 'pagos'])->get();
        return response()->json($cuotas);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'factura_id' => 'required|exists:facturas,id',
            'numero_cuota' => 'required|integer|min:1',
            'monto' => 'required|numeric|min:0',
            'fecha_vencimiento' => 'required|date',
            'estado' => 'nullable|in:pendiente,pagada',
        ]);

        $cuota = Cuota::create($validated);
        return response()->json($cuota, 201);
    }

    public function show(Cuota $cuota)
    {
        return response()->json($cuota->load(['factura', 'pagos']));
    }

    public function update(Request $request, Cuota $cuota)
    {
        $validated = $request->validate([
            'numero_cuota' => 'required|integer|min:1',
            'monto' => 'required|numeric|min:0',
            'fecha_vencimiento' => 'required|date',
            'estado' => 'nullable|in:pendiente,pagada',
        ]);

        $cuota->update($validated);
        return response()->json($cuota);
    }

    public function destroy(Cuota $cuota)
    {
        $cuota->delete();
        return response()->json(null, 204);
    }

    // Método adicional para obtener cuotas por factura
    public function showByFactura($factura_id)
    {
        $cuotas = Cuota::where('factura_id', $factura_id)
                      ->with('pagos')
                      ->get();
        return response()->json($cuotas);
    }

    // Método adicional para obtener cuotas vencidas
    public function showVencidas()
    {
        $cuotas = Cuota::where('estado', 'pendiente')
                      ->whereDate('fecha_vencimiento', '<', now())
                      ->with(['factura.paciente'])
                      ->get();
        return response()->json($cuotas);
    }
}