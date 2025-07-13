<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partido;
use App\Models\Campeonato;

class EnfrentamientoController extends Controller
{
    public function index(Request $request)
    {
        $campeonatos = Campeonato::all();

        $idcampeonato = $request->get('idcampeonato');

        $partidos = Partido::with(['clubLocal', 'clubVisitante', 'clubGanador', 'campeonato'])
            ->when($idcampeonato, fn($query) => $query->where('idcampeonato', $idcampeonato))
            ->orderBy('parfec', 'desc')
            ->get();

        return view('enfrentamientos.index', compact('partidos', 'campeonatos', 'idcampeonato'));
    }
}
