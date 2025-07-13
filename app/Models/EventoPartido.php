<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventoPartido extends Model
{
    protected $primaryKey = 'ideventos_partido';
    protected $table = 'eventos_partidos';

    protected $fillable = [
        'evendescri',
        'evenminu',
        'idpartido',
        'idjugador',
    ];

    public function partido()
    {
        return $this->belongsTo(Partido::class, 'idpartido');
    }

    public function jugador()
    {
        return $this->belongsTo(Jugador::class, 'idjugador');
    }
}
