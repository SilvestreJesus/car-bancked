<?php
use App\Http\Controllers\EquipoController;
use Illuminate\Support\Facades\Route;

// Rutas Públicas de Registro y Login
Route::post('/registrar', [EquipoController::class, 'registrar']);
Route::post('/login', [EquipoController::class, 'login']);

// Ruta para el ESP32 (Lectura)
Route::get('/parametros/{token}', [EquipoController::class, 'obtenerParametros']);

// Ruta para el Dashboard (Escritura/Actualización)
Route::post('/actualizar-parametros', [EquipoController::class, 'actualizarParametros']);