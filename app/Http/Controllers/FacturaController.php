<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Cuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacturaController extends Controller
{
    public function index()
    {
        $facturas = Factura::with(['cita', 'paciente', 'cuotas'])->get();
        return response()->json($facturas);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cita_id' => 'required|exists:citas,id',
            'paciente_id' => 'required|exists:pacientes,id',
            'monto_total' => 'required|numeric|min:0',
            'forma_pago' => 'required|in:contado,cuotas',
            'fecha_emision' => 'required|date',
            'numero_cuotas' => 'required_if:forma_pago,cuotas|integer|min:2',
        ]);

        DB::beginTransaction();
        try {
            // Crear factura
            $factura = Factura::create([
                'cita_id' => $validated['cita_id'],
                'paciente_id' => $validated['paciente_id'],
                'monto_total' => $validated['monto_total'],
                'monto_pagado' => 0,
                'saldo_pendiente' => $validated['monto_total'],
                'forma_pago' => $validated['forma_pago'],
                'estado' => 'pendiente',
                'fecha_emision' => $validated['fecha_emision'],
            ]);

            // Si es pago en cuotas, generar las cuotas
            if ($validated['forma_pago'] === 'cuotas') {
                $numeroCuotas = $validated['numero_cuotas'];
                $montoCuota = round($validated['monto_total'] / $numeroCuotas, 2);
                $fechaVencimiento = now()->addMonth();

                for ($i = 1; $i <= $numeroCuotas; $i++) {
                    // Ajustar última cuota para compensar redondeo
                    $monto = ($i === $numeroCuotas) 
                        ? $validated['monto_total'] - ($montoCuota * ($numeroCuotas - 1))
                        : $montoCuota;

                    Cuota::create([
                        'factura_id' => $factura->id,
                        'numero_cuota' => $i,
                        'monto' => $monto,
                        'fecha_vencimiento' => $fechaVencimiento->copy()->addMonths($i - 1),
                        'estado' => 'pendiente',
                    ]);
                }
            } else {
                // Si es pago al contado, crear una única cuota
                Cuota::create([
                    'factura_id' => $factura->id,
                    'numero_cuota' => 1,
                    'monto' => $validated['monto_total'],
                    'fecha_vencimiento' => $validated['fecha_emision'],
                    'estado' => 'pendiente',
                ]);
            }

            DB::commit();
            return response()->json($factura->load('cuotas'), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(Factura $factura)
    {
        return response()->json($factura->load(['cita', 'paciente', 'cuotas', 'pagos']));
    }

    public function update(Request $request, Factura $factura)
    {
        $validated = $request->validate([
            'estado' => 'nullable|in:pendiente,pagada',
        ]);

        $factura->update($validated);
        return response()->json($factura);
    }

    public function destroy(Factura $factura)
    {
        $factura->delete();
        return response()->json(null, 204);
    }

    // Método adicional para obtener facturas por paciente
    public function showByPaciente($paciente_id)
    {
        $facturas = Factura::where('paciente_id', $paciente_id)
                          ->with(['cita', 'cuotas', 'pagos'])
                          ->get();
        return response()->json($facturas);
    }

    // Método adicional para obtener facturas pendientes
    public function showPendientes()
    {
        $facturas = Factura::where('estado', 'pendiente')
                          ->with(['paciente', 'cuotas'])
                          ->get();
        return response()->json($facturas);
    }
}