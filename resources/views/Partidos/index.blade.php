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
