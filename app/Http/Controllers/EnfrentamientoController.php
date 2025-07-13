<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partido;
use App\Models\Campeonato;

class EnfrentamientoController extends Controller
{
    public function index(Request $request)
    {
        /*$campeonatos = Campeonato::all();

        $idcampeonato = $request->get('idcampeonato');

        $partidos = Partido::with(['clubLocal', 'clubVisitante', 'clubGanador', 'campeonato'])
            ->when($idcampeonato, fn($query) => $query->where('idcampeonato', $idcampeonato))
            ->orderBy('parfec', 'desc')
            ->get();
        return view('enfrentamientos.index', compact('partidos', 'campeonatos', 'idcampeonato'));*/

        $campeonatos = Campeonato::orderBy('canpanio', 'desc')->get();

        $idcampeonato = $request->input('idcampeonato');
        $parfechas = $request->input('parfechas');
        $grupo = $request->input('grupo');

        $query = Partido::with(['local', 'visitante', 'campeonato']);

        if ($idcampeonato) {
            $query->where('idcampeonato', $idcampeonato);
        }

        if ($parfechas) {
            $query->where('parfechas', $parfechas);
        }

        if ($grupo) {
            $query->whereHas('local', function ($q) use ($grupo) {
                $q->where('clubgroup', $grupo);
            })->whereHas('visitante', function ($q) use ($grupo) {
                $q->where('clubgroup', $grupo);
            });
        }

        $partidos = $query->orderBy('parfechas')->get();

        return view('enfrentamientos.index', compact('partidos', 'campeonatos', 'idcampeonato', 'parfechas', 'grupo'));

    }
}
