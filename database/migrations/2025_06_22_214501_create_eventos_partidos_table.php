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
       Schema::create('eventos_partidos', function (Blueprint $table) {
            $table->id('ideventos_partido');
            $table->string('evendescri');//descripcion del evetno
            $table->unsignedBigInteger('evenminu');//minuto del evento
            $table->unsignedBigInteger('idpartido');
            $table->unsignedBigInteger('idjugador');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos_partidos');
    }
};
