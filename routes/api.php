<?php

use App\Http\Controllers\EquipoController;
use Illuminate\Support\Facades\Route;

// --- RUTAS PÚBLICAS ---
Route::post('/registrar', [EquipoController::class, 'registrar']);
Route::post('/login', [EquipoController::class, 'login']);

// --- RUTA PARA EL ROBOT (ESP32) ---
Route::get('/parametros/{token}', [EquipoController::class, 'obtenerParametros']);
// Nueva ruta para que el ESP32 sepa hacia dónde moverse
Route::get('/movimiento/{token}', [EquipoController::class, 'obtenerMovimiento']);

// --- RUTAS PROTEGIDAS (DASHBOARD Y ADMIN) ---
Route::post('/actualizar-parametros', [EquipoController::class, 'actualizarParametros']);
Route::get('/equipos-completo', [EquipoController::class, 'listarTodo']);

// NUEVAS RUTAS PARA EL PANEL DE CONTROL
// Recibe 'F', 'B', 'L', 'R', 'S' (Forward, Back, Left, Right, Stop)
Route::post('/control/mover', [EquipoController::class, 'procesarMovimiento']);
// Recibe el porcentaje de velocidad manual (1-100)
Route::post('/control/velocidad-manual', [EquipoController::class, 'actualizarVelocidadManual']);