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
        Schema::table('detalle_fichas', function (Blueprint $table) {
            $table->string('tipo_traspaso')->default('0');
            $table->string('imagen_fotocopia_cedula')->nullable();
            $table->string('imagen_ficha_medica')->nullable();
            $table->string('imagen_aut_menor')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_fichas', function (Blueprint $table) {
            //
        });
    }
};
