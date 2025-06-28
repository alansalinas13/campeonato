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
        Schema::create('partidos', function (Blueprint $table) {
            $table->id('idpartido');
            $table->unsignedBigInteger('parrond');//ronda del partido
            $table->datetime('parfec');//fecha del partido
            $table->unsignedBigInteger('pargolloc');//goles locales
            $table->unsignedBigInteger('pargolvis');//goles visitantes
            $table->boolean('parpen');//si se llego a la tanda de los penales
            $table->unsignedBigInteger('idcampeonato');
            $table->unsignedBigInteger('idclub_local');
            $table->unsignedBigInteger('idclub_visitante');
            $table->bigInteger('idclub_ganador')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partidos');
    }
};
