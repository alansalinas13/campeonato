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
         Schema::create('campeonatos', function (Blueprint $table) {
            $table->id('idcampeonato');
            $table->string('campnom');
            $table->unsignedBigInteger('canpanio');
            $table->unsignedBigInteger('idclub_campeon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campeonatos');
    }
};
