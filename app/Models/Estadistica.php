<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estadistica extends Model
{
     protected $primaryKey = 'idestadisticas';

    protected $fillable = [
        'estgroup',
        'estparjug',
        'estpargan',
        'estparper',
        'estparemp',
        'estgolafav',
        'estgolencont',
        'estpuntos',
        'idclub',
        'idcampeonato',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class, 'idclub');
    }

    public function campeonato()
    {
        return $this->belongsTo(Campeonato::class, 'idcampeonato');
    }
}
