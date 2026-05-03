<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration { // <--- Importante que diga 'return new class'
    public function up(): void
    {
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_equipo');
            $table->text('integrantes'); 
            $table->string('email')->unique();
            $table->string('password');
            $table->string('token')->unique();
            $table->string('role')->default('usuario'); 

            // Parámetros técnicos 
            $table->float('distancia_detectar')->default(50.0);
            $table->float('distancia_detenerse')->default(10.0);
            $table->float('velocidad_segura')->default(0.5);
            $table->integer('tiempo_respuesta')->default(100);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};