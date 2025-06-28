<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jugador extends Model
{
    protected $primaryKey = 'idjugador';
    protected $fillable = ['jugnom', 'jugest', 'jugtaranar', 'jugtarroj', 'jugsusp', 'idclub'];
    protected $table = 'jugadores';

    public function club()
    {
        return $this->belongsTo(Club::class, 'idclub');
    }
}
