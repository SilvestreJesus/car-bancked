<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_equipo');
            // Usamos text o json para los integrantes del equipo
            $table->text('integrantes'); 
            $table->string('email')->unique();
            $table->string('password');
            
            // Agregamos el rol para la gestión de permisos
            $table->string('role')->default('usuario'); 

            // Parámetros técnicos del carro
            $table->float('distancia_detectar')->default(50.0);  // cm
            $table->float('distancia_detenerse')->default(10.0); // cm
            $table->float('velocidad_segura')->default(0.5);    // m/s
            $table->integer('tiempo_respuesta')->default(100);  // ms
            
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('equipos');
    }
};