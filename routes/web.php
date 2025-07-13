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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::middleware('admin')->group(function () {
        Route::resource('usuarios', UsuarioController::class);
        Route::resource('clubes', ClubController::class);
        Route::resource('campeonatos', CampeonatoController::class);
        Route::resource('jugadores', JugadorController::class);
        Route::resource('partidos', PartidoController::class);
        Route::resource('eventos', EventoPartidoController::class);
        Route::get('/enfrentamientos', [EnfrentamientoController::class, 'index'])->name('enfrentamientos.index');
        Route::get('/campeonatos/{id?}/generar-cuartos', [CampeonatoController::class, 'generarCuartos'])->name('campeonatos.generarCuartos');
        Route::get('/campeonatos/{id}/generar-eliminatorias', [CampeonatoController::class, 'generarSemifinalesYFinal'])->name('campeonatos.generarEliminatorias');
        Route::get('/campeonatos/{id}/asignar-finalistas', [CampeonatoController::class, 'asignarFinalistas'])
            ->name('campeonatos.asignarFinalistas');
        Route::get('/jugadores', [JugadorController::class, 'index'])->name('jugadores.index');
        Route::get('/clubes/{idclub}/jugadores', [JugadorController::class, 'porClub'])->name('jugadores.porClub');
        Route::patch('/jugadores/{id}/restablecer', [JugadorController::class, 'restablecer'])->name('jugadores.restablecer');
    });

    Route::middleware('dirigente')->group(function () {
        Route::get('/mis-jugadores', [/* controlador */])->name('jugadores.dirigente');
    });

    Route::middleware('invitado')->group(function () {
        Route::get('/tabla-posiciones', [/* controlador */])->name('tabla.invitado');
    });
});
Route::get('/posiciones/{idcampeonato?}', [PosicionController::class, 'index'])->name('posiciones.index');


/*Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});*/

require __DIR__ . '/auth.php';
