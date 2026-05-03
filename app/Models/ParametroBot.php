<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParametroBot extends Model
{
    protected $table = 'parametros_bot';
    protected $fillable = ['token', 'distancia_detectar', 'distancia_detenerse', 'velocidad_segura', 'tiempo_respuesta'];
}
