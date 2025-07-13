<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $table;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->table = Schema::table('jugadores', function (Blueprint $table) {
            $table->string('jugcedula')->nullable()->after('jugnom');
            $table->unsignedTinyInteger('jugestado')->default(1)->after('jugcedula'); // 1: inhabilitado
            $table->date('jugfechab')->nullable()->after('jugestado');
            $table->unsignedInteger('jugpart_susp')->default(0)->after('jugsusp');
            $table->unsignedInteger('juggoles')->default(0)->after('jugpart_susp');
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
