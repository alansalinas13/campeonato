<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Club;

class ClubController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $usuario = auth()->user();

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
        return view('clubes.index', compact('clubes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'clubnom' => 'required|string|max:255',
            'clubdescri' => 'nullable|string',
            //'clublogo' => 'nullable|string',
            'clublogo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'clubgroup' => 'nullable|in:A,B',
        ]);
        $data = $request->except('clublogo');

        if ($request->hasFile('clublogo')) {
            $file = $request->file('clublogo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('logos', $filename, 'public');
            $data['clublogo'] = 'storage/' . $path;
        }

        Club::create($data);

        return redirect()->route('clubes.index')->with('success', 'Club creado exitosamente.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clubes.create');
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
        $clube = Club::findOrFail($id);
        //dd(compact('clube'));
        return view('clubes.edit', compact('clube'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'clubnom' => 'required|string|max:255',
            'clubdescri' => 'nullable|string',
            //'clublogo' => 'nullable|string',
            'clublogo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'clubgroup' => 'nullable|in:A,B',
        ]);

        $data = $request->except('clublogo');
        if ($request->hasFile('clublogo')) {
            $file = $request->file('clublogo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('logos', $filename, 'public');
            $data['clublogo'] = 'storage/' . $path;
        }
        $clubes = Club::findOrFail($id);
        $clubes->update($data);

        return redirect()->route('clubes.index')->with('success', 'Club actualizado correctamente.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $clubes = Club::findOrFail($id);
        $clubes->delete();

        return redirect()->route('clubes.index')->with('success', 'Club eliminado correctamente.');

    }
}
