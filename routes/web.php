<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\CampeonatoController;
use App\Http\Controllers\JugadorController;
use App\Http\Controllers\PartidoController;
use App\Http\Controllers\EventoPartidoController;
use App\Http\Controllers\EnfrentamientoController;
use App\Http\Controllers\PosicionController;
use App\Http\Controllers\FichaJugadorController;

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// =====================
// RUTAS GENERALES
// =====================
Route::middleware(['auth'])->group(function () {

    // ADMINISTRADOR
    Route::resource('clubes', ClubController::class);
    Route::resource('jugadores', JugadorController::class);
    Route::middleware('admin')->group(function () {
        Route::resource('usuarios', UsuarioController::class);

        Route::resource('campeonatos', CampeonatoController::class);
        //Route::resource('partidos', PartidoController::class);
        Route::resource('eventos', EventoPartidoController::class);

        // ENFRENTAMIENTOS (solo crud para admin)
        Route::get('/enfrentamientos/create', [
            EnfrentamientoController::class,
            'create'
        ])->name('enfrentamientos.create');
        Route::post('/enfrentamientos', [EnfrentamientoController::class, 'store'])->name('enfrentamientos.store');
        Route::get('/enfrentamientos/{id}/edit', [
            EnfrentamientoController::class,
            'edit'
        ])->name('enfrentamientos.edit');
        Route::put('/enfrentamientos/{id}', [
            EnfrentamientoController::class,
            'update'
        ])->name('enfrentamientos.update');
        Route::delete('/enfrentamientos/{id}', [
            EnfrentamientoController::class,
            'destroy'
        ])->name('enfrentamientos.destroy');

        // Rutas especiales de campeonatos
        Route::get('/campeonatos/{id?}/generar-cuartos', [
            CampeonatoController::class,
            'generarCuartos'
        ])->name('campeonatos.generarCuartos');
        Route::get('/campeonatos/{id}/generar-eliminatorias', [
            CampeonatoController::class,
            'generarSemifinalesYFinal'
        ])->name('campeonatos.generarEliminatorias');
        Route::get('/campeonatos/{id}/asignar-finalistas', [
            CampeonatoController::class,
            'asignarFinalistas'
        ])->name('campeonatos.asignarFinalistas');

        Route::patch('/jugadores/{id}/restablecer', [
            JugadorController::class,
            'restablecer'
        ])->name('jugadores.restablecer');
    });

    // DIRIGENTE
    Route::middleware('dirigente')->group(function () {
        Route::get('/mis-jugadores', [/* controlador */])->name('jugadores.dirigente');
        Route::get('/tabla-posiciones', [/* controlador */])->name('tabla.dirigente');
    });

    // INVITADO
    Route::middleware('invitado')->group(function () {
        Route::get('/tabla-posiciones', [/* controlador */])->name('tabla.invitado');
    });

    // Subida de fichas
    Route::post('/fichas', [FichaJugadorController::class, 'store'])->name('fichas.store');

    // ENFRENTAMIENTOS (todos los logueados pueden ver)
    Route::get('/enfrentamientos', [EnfrentamientoController::class, 'index'])
         ->name('enfrentamientos.index');
    Route::get('/clubes/{idclub}/jugadores', [JugadorController::class, 'porClub'])->name('jugadores.porClub');
});

// Tabla de posiciones pública
Route::get('/posiciones/{idcampeonato?}', [PosicionController::class, 'index'])->name('posiciones.index');
Route::resource('partidos', PartidoController::class);

require __DIR__.'/auth.php';
