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
        $clubes = \App\Models\Club::all();
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
            'clublogo' => 'nullable|string',
            'clubgroup' => 'nullable|in:A,B',
        ]);

        \App\Models\Club::create($request->all());

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
            'clublogo' => 'nullable|string',
            'clubgroup' => 'nullable|in:A,B',
        ]);
        $clubes = Club::findOrFail($id);
        $clubes->update($request->all());

        return redirect()->route('clubes.index')->with('success', 'Club actualizado correctamente.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $clubes = \App\Models\Club::findOrFail($id);
        $clubes->delete();

        return redirect()->route('clubes.index')->with('success', 'Club eliminado correctamente.');

    }
}
