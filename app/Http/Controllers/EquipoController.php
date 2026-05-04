<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\ParametroBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EquipoController extends Controller 
{
    public function registrar(Request $request) {
        $validator = Validator::make($request->all(), [
            'nombre'      => 'required|string|max:255',
            'integrantes' => 'required|string',
            'email'       => 'required|email|unique:equipos,email',
            'password'    => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        try {
            // Generamos un token único basado en el nombre
            $token = strtoupper(substr(str_replace(' ', '', $request->nombre), 0, 3)) . "-" . rand(1000, 9999);

            // 1. Crear Equipo
            $equipo = Equipo::create([
                'nombre_equipo' => $request->nombre,
                'integrantes'   => $request->integrantes,
                'email'         => $request->email,
                'password'      => Hash::make($request->password),
                'token'         => $token,
                'role'          => 'usuario' // Por defecto es usuario
            ]);

            // 2. Crear Parámetros iniciales vinculados
            ParametroBot::create([
                'token' => $token,
                'distancia_detectar'  => 0,
                'distancia_detenerse' => 0,
                'velocidad_segura'    => 0,
                'tiempo_respuesta'    => 0
            ]);

            return response()->json(['status' => 'success', 'token' => $token], 201);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request) {
        $request->validate(['email' => 'required|email', 'password' => 'required']);

        $equipo = Equipo::where('email', $request->email)->first();

        if (!$equipo || !Hash::check($request->password, $equipo->password)) {
            return response()->json(['status' => 'error', 'message' => 'Credenciales incorrectas'], 401);
        }

        return response()->json([
            'status' => 'success',
            'token'  => $equipo->token,
            'role'   => $equipo->role,
            'nombre' => $equipo->nombre_equipo
        ]);
    }

    // LISTADO PARA EL ADMIN
    public function listarTodo() {
        // Trae todos los equipos e incluye su objeto de parámetros
        $equipos = Equipo::with('parametros')->get();
        return response()->json($equipos);
    }

// En EquipoController.php

public function procesarMovimiento(Request $request) {
    // Buscamos los parámetros del bot por su token
    $params = ParametroBot::where('token', $request->token)->first();
    if (!$params) return response()->json(['message' => 'No encontrado'], 404);

    // Actualizamos la dirección (F, B, L, R, S)
    $params->update(['ultimo_movimiento' => $request->direccion]);
    
    return response()->json(['status' => 'success', 'direccion' => $request->direccion]);
}

public function actualizarVelocidadManual(Request $request) {
    $params = ParametroBot::where('token', $request->token)->first();
    if (!$params) return response()->json(['message' => 'No encontrado'], 404);

    $params->update(['velocidad_manual' => $request->velocidad]);
    
    return response()->json(['status' => 'success']);
}

// Este es el que usará el ESP32 para saber qué hacer en tiempo real
public function obtenerMovimiento($token) {
    $params = ParametroBot::where('token', $token)->first();
    if (!$params) return response()->json(['message' => 'No encontrado'], 404);

    return response()->json([
        'mov' => $params->ultimo_movimiento,
        'vel' => $params->velocidad_manual
    ]);
}

    // LECTURA PARA ESP32
// En EquipoController.php -> obtenerParametros
public function obtenerParametros($token) {
    $params = ParametroBot::where('token', $token)->first();
    if (!$params) return response()->json(['message' => 'No encontrado'], 404);

    // EL ESP32 acaba de llamar, marcamos que está "Vivo"
    $params->update(['ultima_conexion' => now()]); 

    return response()->json([
        'd_detectar' => $params->distancia_detectar,
        'd_frenar'   => $params->distancia_detenerse,
        'v_segura'   => $params->velocidad_segura,
        't_resp'     => $params->tiempo_respuesta
    ]);
}
    // ACTUALIZACIÓN DESDE DASHBOARD
    public function actualizarParametros(Request $request) {
        $params = ParametroBot::where('token', $request->token)->first();
        
        if (!$params) return response()->json(['message' => 'No encontrado'], 404);

        $params->update($request->only([
            'distancia_detectar', 
            'distancia_detenerse', 
            'velocidad_segura', 
            'tiempo_respuesta'
        ]));

        return response()->json(['status' => 'success']);
    }
}