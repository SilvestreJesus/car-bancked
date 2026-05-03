<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EquipoController extends Controller 
{
    /**
     * Registro de nuevos equipos y generación de Token.
     */
public function registrar(Request $request) {
    $validator = Validator::make($request->all(), [
        'nombre'      => 'required|string|max:255',
        'integrantes' => 'required|string',
        'email'       => 'required|email|unique:equipos,email',
        'password'    => 'required|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status'  => 'error',
            'message' => 'El correo ya existe o los datos son inválidos.',
            'errors'  => $validator->errors()
        ], 422);
    }

    try {
        // Generar Token único para el Xolo-Bot
        $prefijo = strtoupper(substr($request->nombre, 0, 3)); 
        $token = $prefijo . "-" . rand(1000, 9999) . "-" . bin2hex(random_bytes(2));

        // Registro con los campos de tu modelo
        $equipo = Equipo::create([
            'nombre_equipo' => $request->nombre,
            'integrantes'   => $request->integrantes, 
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'token'         => $token,
            'role'          => 'usuario',
            // Inicializar campos técnicos para evitar el error 500
            'distancia_detectar'  => 0,
            'distancia_detenerse' => 0,
            'velocidad_segura'    => 0,
            'tiempo_respuesta'    => 0 // <--- Importante incluir este según tu modelo
        ]);

        return response()->json([
            'status' => 'success',
            'token'  => $token,
            'role'   => $equipo->role
        ], 201);

// app/Http/Controllers/EquipoController.php
} catch (\Exception $e) {
    return response()->json([
        'status'  => 'error',
        'message' => 'Error de base de datos',
        'error_real' => $e->getMessage() // <--- Esto te dirá exactamente qué columna falta
    ], 500);
}
}

    /**
     * Login para obtener el token y el rol.
     */
    public function login(Request $request) {
        $equipo = Equipo::where('email', $request->email)->first();

        if (!$equipo || !Hash::check($request->password, $equipo->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'token'  => $equipo->token,
            'role'   => $equipo->role,
            'nombre' => $equipo->nombre_equipo
        ]);
    }

    /**
     * Endpoint para el carrito (ESP32) usando el token.
     */
    public function obtenerParametros($token) {
        $equipo = Equipo::where('token', $token)->first();

        if (!$equipo) {
            return response()->json(['message' => 'Token no válido'], 404);
        }

        return response()->json([
            'd_detectar' => $equipo->distancia_detectar,
            'd_frenar'   => $equipo->distancia_detenerse,
            'v_segura'   => $equipo->velocidad_segura
        ]);
    }
}