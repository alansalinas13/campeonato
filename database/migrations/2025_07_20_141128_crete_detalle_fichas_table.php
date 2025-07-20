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
        Schema::create('detalle_fichas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idjugador');
            $table->year('anio');
            $table->string('imagen_ficha');
            $table->enum('tipo_habilitacion', ['2', '3']);
            $table->timestamps();

            $table->foreign('idjugador')->references('idjugador')->on('jugadores')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
