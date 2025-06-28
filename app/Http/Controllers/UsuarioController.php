<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Club;
use App\Http\Controllers\ClubController;
use Illuminate\Support\Facades\Hash;
class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = User::with('club')->get();
       return view('usuarios.index', compact('usuarios'));
      
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
     
     $clubes = Club::all();
     return view('usuarios.create', compact('clubes'));
     //return view('usuarios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed',
        'role' => 'required|in:1,2,3',
        'idclub' => 'nullable|exists:clubes,idclub',
        ]);
    
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'idclub' => $request->idclub,
        ]);
    
        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');

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
        $clubes = \App\Models\Club::all();
        $usuario = User::findOrFail($id);
        return view('usuarios.edit', compact('usuario', 'clubes'));
        //return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $usuario = User::findOrFail($id);
        $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $usuario->id,
        'password' => 'nullable|min:6|confirmed',
        'role' => 'required|in:1,2,3',
        'idclub' => 'nullable|exists:clubes,idclub',
        ]);

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->role = $request->role;
        $usuario->idclub = $request->idclub;

        if ($request->filled('password')) {
            $usuario->password = \Hash::make($request->password);
        }

        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario = User::findOrFail($id);
         $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');

    }
}
