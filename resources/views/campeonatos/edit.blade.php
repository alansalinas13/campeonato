@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Campeonato</h2>

    <form method="POST" action="{{ route('campeonatos.update', $campeonato->idcampeonato) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nombre del Campeonato</label>
            <input type="text" name="campnom" class="form-control" value="{{ old('campnom', $campeonato->campnom) }}" required>
        </div>

        <div class="mb-3">
            <label>Año</label>
            <input type="number" name="canpanio" class="form-control" value="{{ old('canpanio', $campeonato->canpanio) }}" required>
        </div>

        <div class="mb-3">
            <label>Club Campeón (opcional)</label>
            <select name="idclub_campeon" class="form-control">
                <option value="">-- Ninguno --</option>
                @foreach($clubes as $club)
                    <option value="{{ $club->idclub }}" {{ $campeonato->idclub_campeon == $club->idclub ? 'selected' : '' }}>
                        {{ $club->clubnom }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>
@endsection
