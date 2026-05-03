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
        // 1. Validación de los datos de entrada
        $validator = Validator::make($request->all(), [
            'nombre'      => 'required|string|max:255',
            'integrantes' => 'required|string',
            'email'       => 'required|email|unique:equipos,email',
            'password'    => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Datos inválidos o correo ya registrado',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            // 2. Generar el token manual basado en el nombre del Xolo-Bot
            $prefijo = strtoupper(substr($request->nombre, 0, 3)); 
            $token = $prefijo . "-" . rand(1000, 9999) . "-" . bin2hex(random_bytes(2));

            // 3. Crear el registro en la base de datos
            $equipo = Equipo::create([
                'nombre_equipo' => $request->nombre,
                'integrantes'   => $request->integrantes, 
                'email'         => $request->email,
                'password'      => Hash::make($request->password),
                'token'         => $token,
                'role'          => 'usuario', // Rol fijo por defecto en el registro
                
                // Inicializamos los valores técnicos en 0 o nulos para 
                // que no de error 500 si la base de datos no acepta vacíos.
                'distancia_detectar'  => 0,
                'distancia_detenerse' => 0,
                'velocidad_segura'    => 0,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Equipo registrado correctamente',
                'token'  => $token,
                'role'   => $equipo->role
            ], 201);

        } catch (\Exception $e) {
            // En caso de un error de base de datos o conexión
            return response()->json([
                'status'  => 'error',
                'message' => 'Error interno en el servidor de Railway',
                'error'   => $e->getMessage()
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