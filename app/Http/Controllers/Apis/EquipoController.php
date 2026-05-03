<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EquipoController extends Controller 
{
    // Registro de nuevos equipos/usuarios
    public function registrar(Request $request) {
        // 1. Generar el token manual basado en el nombre (como pediste)
        $prefijo = strtoupper(substr($request->nombre, 0, 3)); 
        $token = $prefijo . "-" . rand(1000, 9999) . "-" . bin2hex(random_bytes(2));

        // 2. Crear el registro en la DB con rol 'usuario' por defecto
        $equipo = Equipo::create([
            'nombre_equipo' => $request->nombre,
            'integrantes'   => $request->integrantes, 
            'email'         => $request->email,
            'password'      => Hash::make($request->password), // Usamos Hash por seguridad
            'token'         => $token,
            'role'          => 'usuario' // Se registra como usuario normal
        ]);

        return response()->json([
            'status' => 'success', 
            'token' => $token, 
            'role' => $equipo->role
        ]);
    }

    // Login para identificar el ROL
    public function login(Request $request) {
        $equipo = Equipo::where('email', $request->email)->first();

        if (!$equipo || !Hash::check($request->password, $equipo->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // Devolvemos el rol para que Vue sepa a dónde mandar al usuario
        return response()->json([
            'status' => 'success',
            'token'  => $equipo->token,
            'role'   => $equipo->role, // Aquí Vue sabrá si es 'admin' o 'usuario'
            'nombre' => $equipo->nombre_equipo
        ]);
    }

    // Endpoint para el ESP32 (Parámetros técnicos)
    public function obtenerParametros($token) {
        $equipo = Equipo::where('token', $token)->firstOrFail();
        return response()->json([
            'd_detectar' => $equipo->distancia_detectar,
            'd_frenar'   => $equipo->distancia_detenerse,
            'v_segura'   => $equipo->velocidad_segura
        ]);
    }
}