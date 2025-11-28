<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\OdontologoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\SecretariaController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\HistorialClinicoController;
use App\Http\Controllers\OdontogramaController;
use App\Http\Controllers\TratamientoController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\CuotaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\NotificacionController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas protegidas con Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Roles
    Route::apiResource('roles', RoleController::class);
    
    // Odontólogos
    Route::apiResource('odontologos', OdontologoController::class);
    
    // Pacientes
    Route::apiResource('pacientes', PacienteController::class);
    
    // Secretarias
    Route::apiResource('secretarias', SecretariaController::class);
    
    // Servicios
    Route::apiResource('servicios', ServicioController::class);
    
    // Horarios
    Route::apiResource('horarios', HorarioController::class);
    
    // Citas
    Route::apiResource('citas', CitaController::class);
    Route::post('citas/{cita}/servicios', [CitaController::class, 'attachServicio']);
    
    // Historial Clínico
    Route::apiResource('historial-clinico', HistorialClinicoController::class);
    Route::get('historial-clinico/paciente/{paciente_id}', [HistorialClinicoController::class, 'showByPaciente']);
    
    // Odontograma
    Route::apiResource('odontograma', OdontogramaController::class);
    Route::get('odontograma/paciente/{paciente_id}', [OdontogramaController::class, 'showByPaciente']);
    
    // Tratamientos
    Route::apiResource('tratamientos', TratamientoController::class);
    Route::get('tratamientos/paciente/{paciente_id}', [TratamientoController::class, 'showByPaciente']);
    Route::get('tratamientos/estado/{estado}', [TratamientoController::class, 'showByEstado']);
    
    // Facturas
    Route::apiResource('facturas', FacturaController::class);
    Route::get('facturas/paciente/{paciente_id}', [FacturaController::class, 'showByPaciente']);
    Route::get('facturas/pendientes', [FacturaController::class, 'showPendientes']);
    
    // Cuotas
    Route::apiResource('cuotas', CuotaController::class);
    Route::get('cuotas/factura/{factura_id}', [CuotaController::class, 'showByFactura']);
    Route::get('cuotas/vencidas', [CuotaController::class, 'showVencidas']);
    
    // Pagos
    Route::apiResource('pagos', PagoController::class);
    Route::get('pagos/factura/{factura_id}', [PagoController::class, 'showByFactura']);
    Route::post('pagos/fechas', [PagoController::class, 'showByFechas']);
    
    // Notificaciones
    Route::apiResource('notificaciones', NotificacionController::class);
    Route::get('notificaciones/pendientes', [NotificacionController::class, 'showPendientes']);
    Route::put('notificaciones/{notificacion}/enviar', [NotificacionController::class, 'marcarEnviada']);
});