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
        Schema::create('jugadores', function (Blueprint $table) {
            $table->id('idjugador');
            $table->string('jugnom');
            $table->unsignedBigInteger('jugest');
            $table->unsignedBigInteger('jugtaranar');
            $table->unsignedBigInteger('jugtarroj');
            $table->boolean('jugsusp');
            $table->unsignedBigInteger('idclub')->nullable(); // FK
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jugadores');
    }
};
