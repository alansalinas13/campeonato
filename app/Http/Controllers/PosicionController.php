<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campeonato;
use App\Models\Estadistica;

class PosicionController extends Controller
{
    public function index($idcampeonato = null)
    {
        // Obtener todos los campeonatos ordenados por año descendente
        $campeonatos = Campeonato::orderByDesc('canpanio')->get();

        // Si no hay uno seleccionado, usar el más reciente
        if (!$idcampeonato && $campeonatos->count()) {
            $idcampeonato = $campeonatos->first()->idcampeonato;
        }

        /*$grupoA = Estadistica::with('club')
            ->where('idcampeonato', $idcampeonato)
            ->where('estgroup', 'A')
            ->orderByDesc('estpuntos')
            ->orderByDesc('estgolafav')
            ->get();

        $grupoB = Estadistica::with('club')
            ->where('idcampeonato', $idcampeonato)
            ->where('estgroup', 'B')
            ->orderByDesc('estpuntos')
            ->orderByDesc('estgolafav')
            ->get();*/

        $grupoA = Estadistica::with('club')
            ->where('idcampeonato', $idcampeonato)
            ->where('estgroup', 'A')
            ->orderByDesc('estpuntos')
            ->orderByDesc('estgolafav')
            ->get();
        $grupoB = Estadistica::with('club')
            ->where('idcampeonato', $idcampeonato)
            ->where('estgroup', 'B')
            ->orderByDesc('estpuntos')
            ->orderByDesc('estgolafav')
            ->get();

        return view('estadisticas.index', compact('grupoA', 'grupoB', 'campeonatos', 'idcampeonato'));
    }

}
