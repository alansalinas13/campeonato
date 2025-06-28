@extends('layouts.app')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@section('content')
<div class="container">
    <h2>Crear Partido</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('partidos.store') }}">
        @csrf

        <div class="mb-3">
            <label>Ronda</label>
            <select name="parrond" class="form-control" required>
                @foreach(\App\Models\Partido::rondas() as $key => $nombre)
                    <option value="{{ $key }}">{{ $nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Fecha y hora</label>
            <input type="datetime-local" name="parfec" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Club Local</label>
            <select name="idclub_local" class="form-control" required>
                <option value="">-- Seleccionar --</option>
                @foreach($clubes as $club)
                    <option value="{{ $club->idclub }}">{{ $club->clubnom }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Club Visitante</label>
            <select name="idclub_visitante" class="form-control" required>
                <option value="">-- Seleccionar --</option>
                @foreach($clubes as $club)
                    <option value="{{ $club->idclub }}">{{ $club->clubnom }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Goles Local</label>
            <input type="number" name="pargolloc" class="form-control" value="0" required>
        </div>

        <div class="mb-3">
            <label>Goles Visitante</label>
            <input type="number" name="pargolvis" class="form-control" value="0" required>
        </div>

        <div class="mb-3">
            <label>¿Hubo penales?</label>
            <select name="parpen" class="form-control" required>
                <option value="0">No</option>
                <option value="1">Sí</option>
            </select>
        </div>

        <div class="mb-3">
            <!--<label>Ganador</label>
            <select name="idclub_ganador" class="form-control">
                <option value="">-- Sin jugar --</option>
                <option value="0">Empate</option>
                @foreach($clubes as $club)
                    <option value="{{ $club->idclub }}">{{ $club->clubnom }}</option>
                @endforeach
            </select>-->
            <label>Ganador (opcional)</label>
            <select name="idclub_ganador" class="form-control">
                <option value="">-- Sin jugar --</option>
                <option value="-1" {{ old('idclub_ganador', $partido->idclub_ganador ?? '') == -1 ? 'selected' : '' }}>Empate</option>
                @php
                    $local = $partido->idclub_local ?? old('idclub_local');
                    $visitante = $partido->idclub_visitante ?? old('idclub_visitante');
                @endphp
                @if($local)
                    <option value="{{ $local }}" {{ old('idclub_ganador', $partido->idclub_ganador ?? '') == $local ? 'selected' : '' }}>
                        {{ $clubes->firstWhere('idclub', $local)?->clubnom ?? 'Club Local' }}
                    </option>
                @endif
                @if($visitante)
                    <option value="{{ $visitante }}" {{ old('idclub_ganador', $partido->idclub_ganador ?? '') == $visitante ? 'selected' : '' }}>
                        {{ $clubes->firstWhere('idclub', $visitante)?->clubnom ?? 'Club Visitante' }}
                    </option>
                @endif
            </select>
        </div>

        <div class="mb-3">
            <label>Campeonato</label>
            <select name="idcampeonato" class="form-control" required>
                <option value="">-- Seleccionar --</option>
                @foreach($campeonatos as $camp)
                    <option value="{{ $camp->idcampeonato }}">{{ $camp->campnom }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Crear</button>
    </form>
</div>
@endsection
