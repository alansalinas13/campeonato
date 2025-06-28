<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Models\EventoPartido;
Use App\Models\Partido;
Use App\Models\Jugador;

class EventoPartidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $eventos = EventoPartido::with(['partido', 'jugador'])->get();
    return view('eventos.index', compact('eventos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $partidos = Partido::all();
    $jugadores = Jugador::all();
    return view('eventos.create', compact('partidos', 'jugadores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'evendescri' => 'required|string|max:255',
        'evenminu' => 'required|integer|min:0',
        'idpartido' => 'required|exists:partidos,idpartido',
        'idjugador' => 'required|exists:jugadores,idjugador',
    ]);

    EventoPartido::create($request->only(['evendescri', 'evenminu', 'idpartido', 'idjugador']));

    return redirect()->route('eventos.index')->with('success', 'Evento registrado correctamente.');

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
         $evento = EventoPartido::findOrFail($id);
    $partidos = Partido::all();
    $jugadores = Jugador::all();

    return view('eventos.edit', compact('evento', 'partidos', 'jugadores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $evento = EventoPartido::findOrFail($id);

    $request->validate([
        'evendescri' => 'required|string|max:255',
        'evenminu' => 'required|integer|min:0',
        'idpartido' => 'required|exists:partidos,idpartido',
        'idjugador' => 'required|exists:jugadores,idjugador',
    ]);

    $evento->update($request->only(['evendescri', 'evenminu', 'idpartido', 'idjugador']));

    return redirect()->route('eventos.index')->with('success', 'Evento actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         EventoPartido::destroy($id);
    return redirect()->route('eventos.index')->with('success', 'Evento eliminado.');
    }
}
