@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Crear Campeonato</h2>

    <form method="POST" action="{{ route('campeonatos.store') }}">
        @csrf

        <div class="mb-3">
            <label>Nombre del Campeonato</label>
            <input type="text" name="campnom" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Año</label>
            <input type="number" name="canpanio" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Club Campeón (opcional)</label>
            <select name="idclub_campeon" class="form-control">
                <option value="">-- Ninguno --</option>
                @foreach($clubes as $club)
                    <option value="{{ $club->idclub }}">{{ $club->clubnom }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Crear</button>
        <a href="{{ route('campeonatos.index') }}"
           class="btn btn-secondary"
           style="background:#6c757d; border:none;">
            Cancelar
        </a>
    </form>
</div>
@endsection
