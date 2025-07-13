<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Jugador;

class Club extends Model
{
    protected $table = 'clubes';
    protected $primaryKey = 'idclub';///lo hacemos asi porque el campo id no es el defaul "id"
    protected $fillable = ['clubnom', 'clubdescri', 'clublogo', 'clubgroup'];
    public function jugadores()
    {
        return $this->hasMany(Jugador::class, 'idclub');
    }
}
