<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jugador;
use App\Models\Club;
class JugadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jugadores = Jugador::with('club')->get();
        return view('jugadores.index', compact('jugadores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clubes = Club::all();
        return view('jugadores.create', compact('clubes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'jugnom' => 'required|string|max:255',
        'jugest' => 'required|integer',
        'jugtaranar' => 'required|integer',
        'jugtarroj' => 'required|integer',
        'jugsusp' => 'required|boolean',
        'idclub' => 'nullable|exists:clubes,idclub',
    ]);

    Jugador::create($request->only(['jugnom', 'jugest', 'jugtaranar', 'jugtarroj', 'jugsusp', 'idclub']));

    return redirect()->route('jugadores.index')->with('success', 'Jugador creado correctamente.');

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
        $jugador = Jugador::findOrFail($id);
        $clubes = Club::all();
        return view('jugadores.edit', compact('jugador', 'clubes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $jugador = Jugador::findOrFail($id);

    $request->validate([
        'jugnom' => 'required|string|max:255',
        'jugest' => 'required|integer',
        'jugtaranar' => 'required|integer',
        'jugtarroj' => 'required|integer',
        'jugsusp' => 'required|boolean',
        'idclub' => 'nullable|exists:clubes,idclub',
    ]);

    $jugador->update($request->only(['jugnom', 'jugest', 'jugtaranar', 'jugtarroj', 'jugsusp', 'idclub']));

    return redirect()->route('jugadores.index')->with('success', 'Jugador actualizado correctamente.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Jugador::destroy($id);
        return redirect()->route('jugadores.index')->with('success', 'Jugador eliminado.');
    }
}
