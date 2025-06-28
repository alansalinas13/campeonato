@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Enfrentamientos</h2>

    <form method="GET" class="mb-3">
        <label>Filtrar por campeonato:</label>
        <select name="idcampeonato" class="form-control" onchange="this.form.submit()">
            <option value="">-- Todos --</option>
            @foreach($campeonatos as $camp)
                <option value="{{ $camp->idcampeonato }}" {{ $idcampeonato == $camp->idcampeonato ? 'selected' : '' }}>
                    {{ $camp->campnom }} ({{ $camp->canpanio }})
                </option>
            @endforeach
        </select>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Ronda</th>
                <th>Local</th>
                <th>Visitante</th>
                <th>Goles</th>
                <th>Penales</th>
                <th>Ganador</th>
            </tr>
        </thead>
        <tbody>
            @foreach($partidos as $partido)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($partido->parfec)->format('d/m/Y H:i') }}</td>
                    <td>{{ $partido->nombre_ronda }}</td>
                    <td>{{ $partido->clubLocal->clubnom ?? '---' }}</td>
                    <td>{{ $partido->clubVisitante->clubnom ?? '---' }}</td>
                    <td>{{ $partido->pargolloc }} - {{ $partido->pargolvis }}</td>
                    <td>{{ $partido->parpen ? 'Sí' : 'No' }}</td>
                    <td>
                        @if (is_null($partido->idclub_ganador) || $partido->idclub_ganador == '')
                            <span class="text-muted">Sin jugar</span>
                        @elseif ($partido->idclub_ganador == -1)
                            <span class="text-warning">Empate</span>
                        @else
                            {{ $partido->clubGanador->clubnom ?? 'Desconocido' }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
