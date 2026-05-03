<?php

use App\Http\Controllers\EquipoController;
use Illuminate\Support\Facades\Route;

// --- RUTAS PÚBLICAS ---
Route::post('/registrar', [EquipoController::class, 'registrar']);
Route::post('/login', [EquipoController::class, 'login']);

// --- RUTA PARA EL ROBOT (ESP32) ---
// El ESP32 solo lee, por eso es GET
Route::get('/parametros/{token}', [EquipoController::class, 'obtenerParametros']);

// --- RUTAS PROTEGIDAS (DASHBOARD Y ADMIN) ---
// En el futuro podrías añadir middleware auth:sanctum aquí
Route::post('/actualizar-parametros', [EquipoController::class, 'actualizarParametros']);
Route::get('/equipos-completo', [EquipoController::class, 'listarTodo']);