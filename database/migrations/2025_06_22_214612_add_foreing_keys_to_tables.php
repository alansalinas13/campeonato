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
         //relacion usuarios con clubes (para dirigentes)
       Schema::table('users', function (Blueprint $table) {
            $table->foreign('idclub')->references('idclub')->on('clubes')->onDelete('set null');
        });
///relacion jugador con club
        Schema::table('jugadores', function (Blueprint $table) {
            $table->foreign('idclub')->references('idclub')->on('clubes')->onDelete('set null');
        });

         ////relacion campeonaot con club (club campeon)
        Schema::table('campeonatos', function (Blueprint $table) {
            $table->foreign('idclub_campeon')->references('idclub')->on('clubes')->onDelete('set null');
        });
        //relacion partidos con clubes y campeonato
        Schema::table('partidos', function (Blueprint $table) {
            $table->foreign('idcampeonato')->references('idcampeonato')->on('campeonatos')->onDelete('cascade');
            $table->foreign('idclub_local')->references('idclub')->on('clubes')->onDelete('cascade');
            $table->foreign('idclub_visitante')->references('idclub')->on('clubes')->onDelete('cascade');
        });


       ///relacion eventos partidos con partidos y jugadores
        Schema::table('eventos_partidos', function (Blueprint $table) {
            $table->foreign('idpartido')->references('idpartido')->on('partidos')->onDelete('cascade');
            $table->foreign('idjugador')->references('idjugador')->on('jugadores')->onDelete('cascade');
        });
        
      ///relacion estadisticas con campoenato y clubes
        Schema::table('estadisticas', function (Blueprint $table) {
            $table->foreign('idcampeonato')->references('idcampeonato')->on('campeonatos')->onDelete('cascade');
            $table->foreign('idclub')->references('idclub')->on('clubes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
};
