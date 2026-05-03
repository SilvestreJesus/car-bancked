<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration { // <--- Importante que diga 'return new class'
public function up()
{
Schema::create('parametros_bot', function (Blueprint $table) {
    $table->id();
    $table->string('token'); // Relación con equipos
    $table->integer('distancia_detectar')->default(0);
    $table->integer('distancia_detenerse')->default(0);
    $table->integer('velocidad_segura')->default(0);
    $table->integer('tiempo_respuesta')->default(0);
    $table->timestamps();

    // Vinculamos el token
    $table->foreign('token')->references('token')->on('equipos')->onDelete('cascade');
});
}

    public function down(): void
    {
        Schema::dropIfExists('parametros_bot');
    }
};