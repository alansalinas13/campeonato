@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Lista de Jugadores</h2>
    <a href="{{ route('jugadores.create') }}" class="btn btn-primary mb-3">Crear Jugador</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Amarillas</th>
                <th>Rojas</th>
                <th>Suspendido</th>
                <th>Club</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jugadores as $jugador)
                <tr>
                    <td>{{ $jugador->jugnom }}</td>
                    <td>{{ $jugador->jugest }}</td>
                    <td>{{ $jugador->jugtaranar }}</td>
                    <td>{{ $jugador->jugtarroj }}</td>
                    <td>{{ $jugador->jugsusp ? 'Sí' : 'No' }}</td>
                    <td>{{ $jugador->club?->clubnom ?? '---' }}</td>
                    <td>
                        <a href="{{ route('jugadores.edit', $jugador) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('jugadores.destroy', $jugador) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Eliminar jugador?')">
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
