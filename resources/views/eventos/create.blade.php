@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registrar Evento</h2>

    <form method="POST" action="{{ route('eventos.store') }}">
        @csrf

        <div class="mb-3">
            <label>Descripción del Evento</label>
            <input type="text" name="evendescri" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Minuto</label>
            <input type="number" name="evenminu" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Jugador</label>
            <select name="idjugador" class="form-control" required>
                <option value="">-- Seleccionar --</option>
                @foreach($jugadores as $jugador)
                    <option value="{{ $jugador->idjugador }}">{{ $jugador->jugnom }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Partido</label>
            <select name="idpartido" class="form-control" required>
                <option value="">-- Seleccionar --</option>
                @foreach($partidos as $partido)
                    <option value="{{ $partido->idpartido }}">
                        {{ $partido->clubLocal->clubnom ?? '?' }} vs {{ $partido->clubVisitante->clubnom ?? '?' }} - {{ $partido->parfec }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
    </form>
</div>
@endsection
