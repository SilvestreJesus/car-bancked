<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration { // <--- Importante que diga 'return new class'
public function up()
{
    Schema::create('equipos', function (Blueprint $table) {
        $table->id();
        $table->string('nombre_equipo');
        $table->text('integrantes');
        $table->string('email')->unique();
        $table->string('password');
        $table->string('token')->unique();
        $table->string('role')->default('usuario');
        
        // Campos técnicos con valores por defecto para que no truene el registro
        $table->integer('distancia_detectar')->default(0);
        $table->integer('distancia_detenerse')->default(0);
        $table->integer('velocidad_segura')->default(0);
        $table->integer('tiempo_respuesta')->default(0);
        
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};