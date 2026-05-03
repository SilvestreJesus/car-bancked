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
    $table->string('token')->unique(); // Llave para vincular
    $table->string('role')->default('usuario');
    $table->timestamps();
});
}

    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};