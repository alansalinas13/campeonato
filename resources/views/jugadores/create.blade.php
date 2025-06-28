@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Crear Jugador</h2>

    <form action="{{ route('jugadores.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="jugnom" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Estado</label>
            <input type="number" name="jugest" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Tarjetas Amarillas</label>
            <input type="number" name="jugtaranar" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Tarjetas Rojas</label>
            <input type="number" name="jugtarroj" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>¿Suspendido?</label>
            <select name="jugsusp" class="form-control">
                <option value="0">No</option>
                <option value="1">Sí</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Club (opcional)</label>
            <select name="idclub" class="form-control">
                <option value="">-- Ninguno --</option>
                @foreach($clubes as $club)
                    <option value="{{ $club->idclub }}">{{ $club->clubnom }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Crear</button>
    </form>
</div>
@endsection
