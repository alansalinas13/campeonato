<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campeonato;
use App\Models\Club;
use App\Models\Estadistica;
use App\Models\Partido;
use Illuminate\Support\Carbon;

class CampeonatoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $campeonatos = Campeonato::with('campeon')->get();
        return view('campeonatos.index', compact('campeonatos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'campnom' => 'required|string|max:255',
            'canpanio' => 'required|integer',
            'idclub_campeon' => 'nullable|exists:clubes,idclub',
        ]);

        $campeonato = Campeonato::create($request->only(['campnom', 'canpanio', 'idclub_campeon']));

        $clubes = Club::all();

        foreach ($clubes as $club) {
            Estadistica::create([
                'estgroup' => $club->clubgroup, // 'A' o 'B'
                'estparjug' => 0,
                'estpargan' => 0,
                'estparper' => 0,
                'estparemp' => 0,
                'estgolafav' => 0,
                'estgolencont' => 0,
                'estpuntos' => 0,
                'idclub' => $club->idclub,
                'idcampeonato' => $campeonato->idcampeonato,
            ]);
        }

        // Agrupar clubes por grupo
        $clubesGrupoA = $clubes->where('clubgroup', 'A')->values();
        $clubesGrupoB = $clubes->where('clubgroup', 'B')->values();

///$fecha = Carbon::createFromDate($campeonato->canpanio, 1, 1);
        $fecha = Carbon::createFromDate($campeonato->canpanio, 1, 1)->setTime(15, 0);//para que lo genere con hora

// Generar partidos de ida y vuelta por grupo
        foreach (['A' => $clubesGrupoA, 'B' => $clubesGrupoB] as $grupo => $clubesGrupo) {
            for ($i = 0; $i < $clubesGrupo->count(); $i++) {
                for ($j = $i + 1; $j < $clubesGrupo->count(); $j++) {
                    $local = $clubesGrupo[$i];
                    $visitante = $clubesGrupo[$j];

                    // Ida
                    Partido::create([
                        'parrond' => 1,
                        'parfec' => $fecha,
                        'pargolloc' => 0,
                        'pargolvis' => 0,
                        'parpen' => 0,
                        'idclub_local' => $local->idclub,
                        'idclub_visitante' => $visitante->idclub,
                        'idcampeonato' => $campeonato->idcampeonato,
                    ]);

                    // Vuelta
                    Partido::create([
                        'parrond' => 1,
                        'parfec' => $fecha,
                        'pargolloc' => 0,
                        'pargolvis' => 0,
                        'parpen' => 0,
                        'idclub_local' => $visitante->idclub,
                        'idclub_visitante' => $local->idclub,
                        'idcampeonato' => $campeonato->idcampeonato,
                    ]);
                }
            }
        }

// Interserial: generar partidos entre los "libres"
        for ($i = 0; $i < $clubesGrupoA->count(); $i++) {
            $libreA = $clubesGrupoA[$i];
            $libreB = $clubesGrupoB[$i] ?? null;

            if ($libreB) {

                Partido::create([
                    'parrond' => 1,
                    'parfec' => $fecha,
                    'pargolloc' => 0,
                    'pargolvis' => 0,
                    'parpen' => 0,
                    'idclub_local' => $libreA->idclub,
                    'idclub_visitante' => $libreB->idclub,
                    'idcampeonato' => $campeonato->idcampeonato,
                ]);
                Partido::create([
                    'parrond' => 1,
                    'parfec' => $fecha,
                    'pargolloc' => 0,
                    'pargolvis' => 0,
                    'parpen' => 0,
                    'idclub_local' => $libreB->idclub,
                    'idclub_visitante' => $libreA->idclub,
                    'idcampeonato' => $campeonato->idcampeonato,
                ]);
            }
        }

        return redirect()->route('campeonatos.index')->with('success', 'Campeonato creado correctamente.');

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clubes = Club::all();
        return view('campeonatos.create', compact('clubes'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $campeonato = Campeonato::findOrFail($id);
        $clubes = Club::all();
        return view('campeonatos.edit', compact('campeonato', 'clubes'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Campeonato::destroy($id);
        return redirect()->route('campeonatos.index')->with('success', 'Campeonato eliminado.');

    }

    public function generarCuartos($idcampeonato)
    {
        $grupoA = Estadistica::with('club')
            ->where('idcampeonato', $idcampeonato)
            ->where('estgroup', 'A')
            ->orderByDesc('estpuntos')
            ->orderByDesc('estgolafav')
            ->take(4)
            ->get();

        $grupoB = Estadistica::with('club')
            ->where('idcampeonato', $idcampeonato)
            ->where('estgroup', 'B')
            ->orderByDesc('estpuntos')
            ->orderByDesc('estgolafav')
            ->take(4)
            ->get();

        $fecha = Carbon::now()->addDays(1); // o definir según fixture
        $ronda = 2; // Cuartos de final

        $total = min($grupoA->count(), $grupoB->count(), 4);

        for ($i = 0; $i < $total; $i++) {
            $local = $grupoA[$i]->club;
            $visitante = $grupoB[$total - $i - 1]->club;

            Partido::create([
                'parrond' => $ronda,
                'parfec' => $fecha,
                'pargolloc' => 0,
                'pargolvis' => 0,
                'parpen' => false,
                'idclub_local' => $local->idclub,
                'idclub_visitante' => $visitante->idclub,
                'idcampeonato' => $idcampeonato,
            ]);
        }

        return redirect()->route('enfrentamientos.index')->with('success', 'Cuartos de final generados correctamente.');
    }

    public function generarSemifinalesYFinal($idcampeonato)
    {
        $cuartos = Partido::where('idcampeonato', $idcampeonato)
            ->where('parrond', 2)
            ->whereNotNull('idclub_ganador')
            ->where('idclub_ganador', '>', 0) // evitar empates o sin definir
            ->orderBy('idpartido') // importante para mantener el orden
            ->take(4)
            ->get();

        if ($cuartos->count() < 4) {
            return redirect()->back()->with('error', 'Faltan resultados de cuartos de final para generar semifinales.');
        }

        // Obtener los ganadores
        $ganadores = $cuartos->pluck('idclub_ganador')->all();

        // SEMIFINALES
        $fechaSemis = Carbon::now()->addDays(7);

        $semifinal1 = Partido::create([
            'parrond' => 3,
            'parfec' => $fechaSemis,
            'pargolloc' => 0,
            'pargolvis' => 0,
            'parpen' => false,
            'idclub_local' => $ganadores[0],
            'idclub_visitante' => $ganadores[3],
            'idcampeonato' => $idcampeonato,
        ]);

        $semifinal2 = Partido::create([
            'parrond' => 3,
            'parfec' => $fechaSemis,
            'pargolloc' => 0,
            'pargolvis' => 0,
            'parpen' => false,
            'idclub_local' => $ganadores[1],
            'idclub_visitante' => $ganadores[2],
            'idcampeonato' => $idcampeonato,
        ]);

        // FINAL
        $fechaFinal = Carbon::now()->addDays(21);

        // Creamos el registro del partido final con equipos en null, se completan luego
        Partido::create([
            'parrond' => 4,
            'parfec' => $fechaFinal,
            'pargolloc' => 0,
            'pargolvis' => 0,
            'parpen' => false,
            'idclub_local' => null, // se completará tras definir semifinales
            'idclub_visitante' => null,
            'idcampeonato' => $idcampeonato,
        ]);

        return redirect()->route('enfrentamientos.index')->with('success', 'Semifinales y Final generadas correctamente.');
    }

    public function asignarFinalistas($idcampeonato)
    {
        // Buscar semifinales jugadas
        $semis = Partido::where('idcampeonato', $idcampeonato)
            ->where('parrond', 3)
            ->whereNotNull('idclub_ganador')
            ->where('idclub_ganador', '>', 0)
            ->orderBy('idpartido')
            ->take(2)
            ->get();

        if ($semis->count() < 2) {
            return redirect()->back()->with('error', 'Aún no se han definido los dos ganadores de semifinales.');
        }

        $ganadores = $semis->pluck('idclub_ganador');

        // Buscar la final
        $final = Partido::where('idcampeonato', $idcampeonato)
            ->where('parrond', 4)
            ->first();

        if (!$final) {
            return redirect()->back()->with('error', 'No se encontró partido de final para este campeonato.');
        }

        // Actualizar la final
        $final->update([
            'idclub_local' => $ganadores[0],
            'idclub_visitante' => $ganadores[1],
        ]);

        return redirect()->route('enfrentamientos.index')->with('success', 'Final actualizada con los equipos finalistas.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $campeonato = Campeonato::findOrFail($id);

        $request->validate([
            'campnom' => 'required|string|max:255',
            'canpanio' => 'required|integer',
            'idclub_campeon' => 'nullable|exists:clubes,idclub',
        ]);

        $campeonato->update($request->all());

        return redirect()->route('campeonatos.index')->with('success', 'Campeonato actualizado correctamente.');

    }


}
