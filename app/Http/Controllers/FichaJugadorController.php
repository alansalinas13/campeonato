<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jugador;
use App\Models\DetalleFicha;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\DocBlock\Description;

class FichaJugadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'idjugador' => 'required|exists:jugadores,idjugador',
            'anio' => 'required|integer|min:2000|max:' . date('Y'),
            'tipo_habilitacion' => 'required|in:2,3',
            'imagen_ficha' => 'required|image|mimes:jpeg,png,jpg|max:20480',// 20MB
        ]);

        $path = $request->file('imagen_ficha')->store('fichas', 'public');

        $data = $request->only(['idjugador', 'anio', 'tipo_habilitacion']);

        // Buscar ficha existente
        $ficha = DetalleFicha::where('idjugador', $data['idjugador'])
            ->where('anio', $data['anio'])
            ->where('tipo_habilitacion', $data['tipo_habilitacion'])
            ->first();
        if ($ficha) {
            $data['imagen_ficha'] = 'storage/' . $path;
            if ($ficha && $ficha->imagen_ficha && Storage::disk('public')->exists(str_replace('storage/', '', $ficha->imagen_ficha))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $ficha->imagen_ficha));
            }
            $ficha->update($data);
        } else {
            // Guardar detalle de ficha
            DetalleFicha::create([
                'idjugador' => $request->idjugador,
                'anio' => $request->anio,
                'tipo_habilitacion' => $request->tipo_habilitacion,
                'imagen_ficha' => 'storage/' . $path,
            ]);
        }

        // Actualizar jugador
        $jugador = Jugador::findOrFail($request->idjugador);
        $jugador->jugest = (int)$request->tipo_habilitacion;
        $jugador->jugfechab = now();
        $jugador->save();

        return redirect()->route('jugadores.porClub', $jugador->idclub)->with('success', 'Jugador habilitado en ' . strtoupper($request->tipo_habilitacion));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
