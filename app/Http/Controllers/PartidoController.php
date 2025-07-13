<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Club;
use App\Models\Campeonato;
use App\Models\Partido;
use App\Models\Estadistica;
use App\Models\Jugador;
use App\Models\EventoPartido;

class PartidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /*$partidos = Partido::with(['clubLocal', 'clubVisitante', 'clubGanador', 'campeonato'])->get();
        return view('partidos.index', compact('partidos'));*/
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

        return view('partidos.index', compact('partidos', 'campeonatos', 'idcampeonato', 'parfechas', 'grupo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'parrond' => 'required|integer|min:1',
            'parfec' => 'required|date',
            'pargolloc' => 'required|integer|min:0',
            'pargolvis' => 'required|integer|min:0',
            'parfechas' => 'required|integer|min:1',
            'parpen' => 'required|boolean',
            'idcampeonato' => 'required|exists:campeonatos,idcampeonato',
            'idclub_local' => 'required|exists:clubes,idclub',
            'idclub_visitante' => 'required|exists:clubes,idclub|different:idclub_local',
            'idclub_ganador' => [
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    if (!in_array((int)$value, [-1, null, (int)$request->idclub_local, (int)$request->idclub_visitante], true)) {
                        $fail('El club ganador debe ser uno de los clubes del partido o empate.');
                    }
                },
            ],
        ]);

        $partido = Partido::create($request->only([
            'parrond', 'parfec', 'pargolloc', 'pargolvis', 'parpen',
            'idcampeonato', 'idclub_local', 'idclub_visitante', 'idclub_ganador', 'parfechas'
        ]));
        // Eliminar eventos anteriores si es edición
        if ($partido->exists) {
            EventoPartido::where('idpartido', $partido->idpartido)->delete();
        }
        // Guardar nuevos eventos
        if ($request->has('evento')) {
            foreach ($request->evento as $ev) {
                EventoPartido::create([
                    'evenminu' => $ev['minuto'],
                    'evendescri' => $ev['descripcion'],
                    'idjugador' => $ev['jugador'],
                    'idpartido' => $partido->idpartido,
                ]);
            }
        }
        // Solo actualizamos estadísticas si es ronda 1 (fase de grupos) y si el partido ya tiene resultado
        if ($partido->parrond == 1 && ($partido->idclub_ganador !== null && $partido->idclub_ganador !== '')) {
            $this->actualizarEstadisticasPartido($partido, [
                'parrond' => 1,
                'pargolloc' => 0,
                'pargolvis' => 0,
                'idclub_ganador' => null
            ]);
        }

        return redirect()->route('partidos.index')->with('success', 'Partido creado correctamente.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clubes = Club::all();
        $campeonatos = Campeonato::all();
        $jugadores = Jugador::all();
        return view('partidos.create', compact('clubes', 'campeonatos', 'jugadores'));
    }

    private function actualizarEstadisticasPartido(Partido $partido, $original)
    {
        // Estadísticas actuales
        $local = Estadistica::where('idcampeonato', $partido->idcampeonato)
            ->where('idclub', $partido->idclub_local)->first();

        $visitante = Estadistica::where('idcampeonato', $partido->idcampeonato)
            ->where('idclub', $partido->idclub_visitante)->first();

        if (!$local || !$visitante) return;

        // Si ya había datos cargados, revertimos lo anterior
        if ($original['pargolloc'] > 0 || $original['pargolvis'] > 0 || ($original['idclub_ganador'] !== null && $original['idclub_ganador'] !== '')) {
            // Goles
            $local->estparjug -= 1;
            $visitante->estparjug -= 1;

            $local->estgolafav -= $original['pargolloc'];
            $local->estgolencont -= $original['pargolvis'];

            $visitante->estgolafav -= $original['pargolvis'];
            $visitante->estgolencont -= $original['pargolloc'];

            // Resultado anterior
            if ($original['pargolloc'] > $original['pargolvis']) {
                $local->estpargan -= 1;
                $visitante->estparper -= 1;
                $local->estpuntos -= 3;
            } elseif ($original['pargolloc'] < $original['pargolvis']) {
                $visitante->estpargan -= 1;
                $local->estparper -= 1;
                $visitante->estpuntos -= 3;
            } elseif ($original['pargolloc'] == $original['pargolvis'] && $original['idclub_ganador'] == '-1') {
                $local->estparemp -= 1;
                $visitante->estparemp -= 1;
                $local->estpuntos -= 1;
                $visitante->estpuntos -= 1;
            }
        }

        // Ahora aplicamos el nuevo resultado
        $golesLoc = $partido->pargolloc;
        $golesVis = $partido->pargolvis;

        $local->estparjug += 1;
        $visitante->estparjug += 1;

        $local->estgolafav += $golesLoc;
        $local->estgolencont += $golesVis;

        $visitante->estgolafav += $golesVis;
        $visitante->estgolencont += $golesLoc;

        if ($golesLoc > $golesVis) {
            $local->estpargan += 1;
            $visitante->estparper += 1;
            $local->estpuntos += 3;
        } elseif ($golesVis > $golesLoc) {
            $visitante->estpargan += 1;
            $local->estparper += 1;
            $visitante->estpuntos += 3;
        } elseif ($golesLoc == $golesVis && $partido->idclub_ganador == '-1') {
            $local->estparemp += 1;
            $visitante->estparemp += 1;
            $local->estpuntos += 1;
            $visitante->estpuntos += 1;


        }
        $local->save();
        $visitante->save();
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
        $partido = Partido::findOrFail($id);
        $clubes = Club::all();
        $campeonatos = Campeonato::all();
        $jugadores = Jugador::all();
        //$eventopartido = EventoPartido::where('idpartido', $partido->idpartido)->get();
        $partido = Partido::with('eventos')->findOrFail($id);
        //$local = Estadistica::where('idcampeonato', $partido->idcampeonato)
        // ->where('idclub', $partido->idclub_local)->first();
//dd($partido);
        return view('partidos.edit', compact('partido', 'clubes', 'campeonatos', 'jugadores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $partido = Partido::findOrFail($id);

        $request->validate([
            'parrond' => 'required|integer|min:1',
            'parfec' => 'required|date',
            'pargolloc' => 'required|integer|min:0',
            'pargolvis' => 'required|integer|min:0',
            'parfechas' => 'required|integer|min:1',
            'parpen' => 'required|boolean',
            'idcampeonato' => 'required|exists:campeonatos,idcampeonato',
            'idclub_local' => 'required|exists:clubes,idclub',
            'idclub_visitante' => 'required|exists:clubes,idclub|different:idclub_local',
            'idclub_ganador' => [
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    if (!in_array((int)$value, [-1, null, (int)$request->idclub_local, (int)$request->idclub_visitante], true)) {
                        $fail('El club ganador debe ser uno de los clubes del partido o empate.');
                    }
                },
            ],
        ]);
        $datosOriginales = $partido->only(['parrond', 'pargolloc', 'pargolvis', 'idclub_ganador']);
        $partido->update($request->only([
            'parrond', 'parfec', 'pargolloc', 'pargolvis', 'parpen',
            'idcampeonato', 'idclub_local', 'idclub_visitante', 'idclub_ganador', 'parfechas'
        ]));
        // Eliminar eventos anteriores si es edición
        if ($partido->exists) {
            EventoPartido::where('idpartido', $partido->idpartido)->delete();
        }
        // Guardar nuevos eventos
        if ($request->has('evento')) {
            foreach ($request->evento as $ev) {
                EventoPartido::create([
                    'evenminu' => $ev['minuto'],
                    'evendescri' => $ev['descripcion'],
                    'idjugador' => $ev['jugador'],
                    'idpartido' => $partido->idpartido,
                ]);

                if ($ev['descripcion'] === 'Gol') {
                    Jugador::where('idjugador', $ev['jugador'])->increment('juggoles');
                }

                if ($ev['descripcion'] === 'Tarjeta Amarilla') {
                    $jugador = Jugador::find($ev['jugador']);
                    $jugador->jugtaranar += 1;

                    if ($jugador->jugtaranar >= 3) {
                        $jugador->jugsusp = true;
                    }

                    $jugador->save();
                }

                if ($ev['descripcion'] === 'Tarjeta Roja') {
                    $jugador = Jugador::find($ev['jugador']);
                    $jugador->jugsusp = true;
                    $jugador->save();
                }
            }
        }
        // Solo actualizamos estadísticas si es ronda 1
        if ($partido->parrond == 1 && ($partido->idclub_ganador !== null && $partido->idclub_ganador !== '')) {
            //$this->actualizarEstadisticas($partido->idcampeonato);
            $this->actualizarEstadisticasPartido($partido, $datosOriginales);
        }

        return redirect()->route('partidos.index')->with('success', 'Partido actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Partido::destroy($id);
        return redirect()->route('partidos.index')->with('success', 'Partido eliminado.');
    }


}
