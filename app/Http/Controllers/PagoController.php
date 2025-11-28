<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Cuota;
use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::with(['cuota', 'factura'])->get();
        return response()->json($pagos);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cuota_id' => 'required|exists:cuotas,id',
            'monto_pagado' => 'required|numeric|min:0',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia',
            'fecha_pago' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            // Obtener la cuota
            $cuota = Cuota::findOrFail($validated['cuota_id']);
            
            // Crear el pago
            $pago = Pago::create([
                'cuota_id' => $validated['cuota_id'],
                'factura_id' => $cuota->factura_id,
                'monto_pagado' => $validated['monto_pagado'],
                'metodo_pago' => $validated['metodo_pago'],
                'fecha_pago' => $validated['fecha_pago'],
            ]);

            // Actualizar estado de la cuota
            $cuota->update(['estado' => 'pagada']);

            // Actualizar factura
            $factura = Factura::findOrFail($cuota->factura_id);
            $factura->monto_pagado += $validated['monto_pagado'];
            $factura->saldo_pendiente -= $validated['monto_pagado'];
            
            // Si ya no hay saldo pendiente, marcar factura como pagada
            if ($factura->saldo_pendiente <= 0) {
                $factura->estado = 'pagada';
            }
            
            $factura->save();

            DB::commit();
            return response()->json($pago->load(['cuota', 'factura']), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(Pago $pago)
    {
        return response()->json($pago->load(['cuota', 'factura']));
    }

    public function destroy(Pago $pago)
    {
        DB::beginTransaction();
        try {
            // Obtener datos antes de eliminar
            $cuota = $pago->cuota;
            $factura = $pago->factura;
            $montoPagado = $pago->monto_pagado;

            // Eliminar pago
            $pago->delete();

            // Actualizar cuota
            $cuota->update(['estado' => 'pendiente']);

            // Actualizar factura
            $factura->monto_pagado -= $montoPagado;
            $factura->saldo_pendiente += $montoPagado;
            $factura->estado = 'pendiente';
            $factura->save();

            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Método adicional para obtener pagos por factura
    public function showByFactura($factura_id)
    {
        $pagos = Pago::where('factura_id', $factura_id)
                    ->with('cuota')
                    ->get();
        return response()->json($pagos);
    }

    // Método adicional para obtener pagos por rango de fechas
    public function showByFechas(Request $request)
    {
        $validated = $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $pagos = Pago::whereBetween('fecha_pago', [$validated['fecha_inicio'], $validated['fecha_fin']])
                    ->with(['cuota', 'factura.paciente'])
                    ->get();
        
        return response()->json($pagos);
    }
}