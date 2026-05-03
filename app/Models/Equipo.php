<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Equipo extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'equipos'; // Nombre exacto de tu migración

    protected $fillable = [
        'nombre_equipo',
        'integrantes',
        'email',
        'password',
        'role',
        'distancia_detectar',
        'distancia_detenerse',
        'velocidad_segura',
        'tiempo_respuesta'
    ];

    protected $hidden = [
        'password',
    ];
}