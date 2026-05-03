<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Equipo extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'equipos';

    protected $fillable = ['nombre_equipo', 'integrantes', 'email', 'password', 'token', 'role'];

// Relación: Un equipo tiene sus parámetros
public function parametros() {
    return $this->hasOne(ParametroBot::class, 'token', 'token');
}
}