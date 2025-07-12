<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partido extends Model
{
     protected $primaryKey = 'idpartido';

    protected $fillable = [
        'parrond',
        'parfec',
        'pargolloc',
        'pargolvis',
        'parpen',
        'idcampeonato',
        'idclub_local',
        'idclub_visitante',
        'idclub_ganador',
    ];

    public function campeonato()
    {
        return $this->belongsTo(Campeonato::class, 'idcampeonato');
    }

    public function clubLocal()
    {
        return $this->belongsTo(Club::class, 'idclub_local');
    }

    public function clubVisitante()
    {
        return $this->belongsTo(Club::class, 'idclub_visitante');
    }

    public function clubGanador()
    {
        return $this->belongsTo(Club::class, 'idclub_ganador');
    }

    public static function rondas()
    {
    return [
        1 => 'Fase de grupos',
        2 => 'Cuartos de final',
        3 => 'Semifinales',
        4 => 'Final',
        5 => 'Partidos extras',
    ];
    }

    public function getNombreRondaAttribute()
    {
        return self::rondas()[$this->parrond] ?? 'Desconocido';
    }
    public function eventos()
    {
        return $this->hasMany(EventoPartido::class, 'idpartido');
    }
}
