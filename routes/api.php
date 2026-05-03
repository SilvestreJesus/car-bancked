<?php
use App\Http\Controllers\EquipoController;
use Illuminate\Support\Facades\Route;

Route::post('/registrar', [EquipoController::class, 'registrar']);
Route::post('/login', [EquipoController::class, 'login']);
Route::get('/parametros/{token}', [EquipoController::class, 'obtenerParametros']);