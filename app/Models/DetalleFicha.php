<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleFicha extends Model
{
    protected $fillable = ['idjugador', 'anio', 'imagen_ficha', 'tipo_habilitacion'];
}
