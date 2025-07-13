@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Enfrentamientos</h2>

        <form method="GET" class="mb-3">
            <div class="col-md-4">
                <label>Filtrar por campeonato:</label>
                <select name="idcampeonato" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Todos --</option>
                    @foreach($campeonatos as $camp)
                        <option
                            value="{{ $camp->idcampeonato }}" {{ $idcampeonato == $camp->idcampeonato ? 'selected' : '' }}>
                            {{ $camp->campnom }} ({{ $camp->canpanio }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Fecha (jornada)</label>
                <input type="number" name="parfechas" class="form-control" value="{{ $parfechas }}">
            </div>
            <div class="col-md-3">
                <label>Grupo</label>
                <select name="grupo" class="form-control">
                    <option value="">-- Todos --</option>
                    <option value="A" {{ $grupo == 'A' ? 'selected' : '' }}>Grupo A</option>
                    <option value="B" {{ $grupo == 'B' ? 'selected' : '' }}>Grupo B</option>
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </form>

        <table class="table">
            <thead>
            <tr>
                <th>Fecha N°</th>
                <th>Grupo</th>
                <th>Día</th>
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
                    <td>{{ $partido->parfechas }}</td>
                    <td>{{ $partido->local->clubgroup == $partido->visitante->clubgroup ? $partido->local->clubgroup : 'INTERSERIAL'  }}</td>
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
