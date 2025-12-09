{{--
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Lista de Partidos</h2>
    <a href="{{ route('partidos.create') }}" class="btn btn-primary mb-3">Crear Partido</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Ronda</th>
                <th>Fecha</th>
                <th>Día Partido</th>
                <th>Local</th>
                <th>Visitante</th>
                <th>Goles Local</th>
                <th>Goles Visitante</th>
                <th>Penales</th>
                <th>Ganador</th>
                <th>Campeonato</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($partidos as $partido)
                <tr>
                    <td>{{ $partido->nombre_ronda }}</td>
                    <td>{{ $partido->parfechas }}</td>
                    <td>{{ $partido->parfec }}</td>
                    <td>{{ $partido->clubLocal->clubnom ?? '---' }}</td>
                    <td>{{ $partido->clubVisitante->clubnom ?? '---' }}</td>
                    <td>{{ $partido->pargolloc }}</td>
                    <td>{{ $partido->pargolvis }}</td>
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
                    <td>{{ $partido->campeonato->campnom ?? '---' }}</td>
                    <td>
                        <a href="{{ route('partidos.edit', $partido) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('partidos.destroy', $partido) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Eliminar este partido?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
--}}


@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Enfrentamientos</h2>

        <form method="GET" action="{{ route('partidos.index') }}" class="row g-2 mb-4">
            <div class="col-md-4">
                <label>Campeonato</label>
                <select name="idcampeonato" class="form-control">
                    <option value="">-- Todos --</option>
                    @foreach($campeonatos as $cam)
                        <option
                            value="{{ $cam->idcampeonato }}" {{ $idcampeonato == $cam->idcampeonato ? 'selected' : '' }}>
                            {{ $cam->campnom }}
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

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Fecha N°</th>
                <th>Día</th>
                <th>Campeonato</th>
                <th>Local</th>
                <th>Goles Loc</th>
                <th>Visitante</th>
                <th>Goles Vis</th>
                <th>Grupo</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse($partidos as $p)
                <tr>
                    <td>{{ $p->parfechas }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->parfec)->format('d/m/Y') }}</td>
                    <td>{{ $p->campeonato->camanio }}</td>
                    <td>{{ $p->local->clubnom }}</td>
                    <td>{{ $p->pargolloc }}</td>
                    <td>{{ $p->visitante->clubnom }}</td>
                    <td>{{ $p->pargolvis }}</td>
                    <td>{{ $p->local->clubgroup == $p->visitante->clubgroup ? $p->local->clubgroup : 'INTERSERIAL'  }}</td>
                    <td>
                        <a href="{{ route('partidos.edit', $p) }}" class="btn btn-sm btn-warning">Cargar Eventos</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No se encontraron partidos con esos filtros.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
