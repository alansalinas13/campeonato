@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Eventos de Partidos</h2>
    <a href="{{ route('eventos.create') }}" class="btn btn-primary mb-3">Registrar Evento</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Descripción</th>
                <th>Minuto</th>
                <th>Jugador</th>
                <th>Partido</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($eventos as $evento)
                <tr>
                    <td>{{ $evento->evendescri }}</td>
                    <td>{{ $evento->evenminu }}</td>
                    <td>{{ $evento->jugador->jugnom ?? '---' }}</td>
                    <td>
                        {{ $evento->partido->clubLocal->clubnom ?? '?' }}
                        vs
                        {{ $evento->partido->clubVisitante->clubnom ?? '?' }}
                    </td>
                    <td>
                        <a href="{{ route('eventos.edit', $evento) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('eventos.destroy', $evento) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Eliminar evento?')">
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
