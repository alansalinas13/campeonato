<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Club;

class Campeonato extends Model
{
    protected $primaryKey = 'idcampeonato';
    protected $fillable = ['campnom', 'canpanio', 'idclub_campeon'];

    public function campeon()
    {
        return $this->belongsTo(Club::class, 'idclub_campeon');
    }

}
