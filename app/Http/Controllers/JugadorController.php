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
        $usuario = auth()->user();
        //$jugadores = Jugador::with('club')->get();
        //return view('jugadores.index', compact('jugadores'));

        // Invitados no tienen acceso
        if ($usuario->role === 3) {
            abort(403, 'Acceso no autorizado.');
        }

        // Admin: ve todos los clubes
        if ($usuario->role === 1) {
            $clubes = Club::orderBy('clubnom')->get();
        } else {// Dirigente: ve solo su club
            $clubes = Club::where('idclub', $usuario->idclub)->get();
        }

        return view('jugadores.index', compact('clubes'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jugnom' => 'required|string|max:255',
            'jugcedula' => 'nullable|string|max:20',
            'jugest' => 'required|integer',
            'jugfechab' => 'nullable|date',
            'jugtaranar' => 'required|integer',
            'jugsusp' => 'required|boolean',
            'jugpart_susp' => 'nullable|integer|min:0',
            'juggoles' => 'nullable|integer|min:0',
            'idclub' => 'nullable|exists:clubes,idclub',
        ]);
        Jugador::create($request->only(['jugnom', 'jugcedula', 'jugfechab', 'jugest', 'jugtaranar', 'jugsusp', 'jugpart_susp', 'juggoles', 'idclub']));

        return redirect()->route('jugadores.index')->with('success', 'Jugador creado correctamente.');

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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Jugador::destroy($id);
        return redirect()->route('jugadores.index')->with('success', 'Jugador eliminado.');
    }

    public function porClub($idclub)
    {
        $usuario = auth()->user();

        // Invitados no pueden acceder
        if ($usuario->role === 'invitado') {
            abort(403, 'Acceso no autorizado.');
        }

        // Dirigente solo puede acceder a su club
        if ($usuario->role === 'dirigente' && $usuario->idclub != $idclub) {
            abort(403, 'No puede acceder a este club.');
        }

        $club = Club::with('jugadores')->findOrFail($idclub);

        return view('jugadores.por_club', compact('club'));
    }

    public function restablecer($id)
    {
        $jugador = Jugador::findOrFail($id);
        $jugador->update([
            'jugtaranar' => 0,
            'suspendido' => false,
            'jugpart_susp' => 0,
        ]);

        return back()->with('success', 'Tarjetas y suspensión restablecidas.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $jugador = Jugador::findOrFail($id);

        $request->validate([
            'jugnom' => 'required|string|max:255',
            'jugcedula' => 'nullable|string|max:20',
            'jugest' => 'required|integer',
            'jugfechab' => 'nullable|date',
            'jugtaranar' => 'required|integer',
            'jugsusp' => 'required|boolean',
            'jugpart_susp' => 'nullable|integer|min:0',
            'juggoles' => 'nullable|integer|min:0',
            'idclub' => 'nullable|exists:clubes,idclub',
        ]);
        $jugador->update($request->only(['jugnom', 'jugcedula', 'jugfechab', 'jugest', 'jugtaranar', 'jugsusp', 'jugpart_susp', 'juggoles', 'idclub']));

        return redirect()->route('jugadores.index')->with('success', 'Jugador actualizado correctamente.');

    }


}
