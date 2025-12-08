@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Evento</h2>

    <form method="POST" action="{{ route('eventos.update', $evento->ideventos_partido) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Descripción del Evento</label>
            <input type="text" name="evendescri" class="form-control" value="{{ old('evendescri', $evento->evendescri) }}" required>
        </div>

        <div class="mb-3">
            <label>Minuto</label>
            <input type="number" name="evenminu" class="form-control" value="{{ old('evenminu', $evento->evenminu) }}" required>
        </div>

        <div class="mb-3">
            <label>Jugador</label>
            <select name="idjugador" class="form-control" required>
                @foreach($jugadores as $jugador)
                    <option value="{{ $jugador->idjugador }}" {{ $jugador->idjugador == $evento->idjugador ? 'selected' : '' }}>
                        {{ $jugador->jugnom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Partido</label>
            <select name="idpartido" class="form-control" required>
                @foreach($partidos as $partido)
                    <option value="{{ $partido->idpartido }}" {{ $partido->idpartido == $evento->idpartido ? 'selected' : '' }}>
                        {{ $partido->clubLocal->clubnom ?? '?' }} vs {{ $partido->clubVisitante->clubnom ?? '?' }} - {{ $partido->parfec }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('eventos.index') }}"
           class="btn btn-secondary"
           style="background:#6c757d; border:none;">
            Cancelar
        </a>
    </form>
</div>
@endsection
