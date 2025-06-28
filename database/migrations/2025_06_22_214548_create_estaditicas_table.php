<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('estadisticas', function (Blueprint $table) {
            $table->id('idestadisticas');
            $table->enum('estgroup', ['A', 'B'])->nullable();
            $table->unsignedBigInteger('estparjug');//partidos jugados
            $table->unsignedBigInteger('estpargan');// partidos ganados 
            $table->unsignedBigInteger('estparper');//partidos perdidos
            $table->unsignedBigInteger('estparemp');//partidos empatados
            $table->unsignedBigInteger('estgolafav');//goles a favor
            $table->unsignedBigInteger('estgolencont');//goles en contra
            $table->unsignedBigInteger('estpuntos');//puntos en base a partidos ganados, empatados y perdidos
            $table->unsignedBigInteger('idclub');
            $table->unsignedBigInteger('idcampeonato');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estaditicas');
    }
};
