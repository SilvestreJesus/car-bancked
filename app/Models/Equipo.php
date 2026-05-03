<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Equipo extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'equipos';

// app/Models/Equipo.php
protected $fillable = [
    'nombre_equipo',
    'integrantes',
    'email',
    'password',
    'token',
    'role',
    'distancia_detectar',
    'distancia_detenerse',
    'velocidad_segura',
    'tiempo_respuesta' // <--- Asegúrate de que este no falte
];
    protected $hidden = [
        'password',
    ];
}