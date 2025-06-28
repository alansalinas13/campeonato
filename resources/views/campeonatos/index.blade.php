@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Lista de Campeonatos</h2>
    <a href="{{ route('campeonatos.create') }}" class="btn btn-primary mb-3">Crear Campeonato</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Año</th>
                <th>Club Campeón</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($campeonatos as $campeonato)
                <tr>
                    <td>{{ $campeonato->campnom }}</td>
                    <td>{{ $campeonato->canpanio }}</td>
                    <td>{{ $campeonato->campeon?->clubnom ?? '---' }}</td>
                    <td>
                        <a href="{{ route('campeonatos.edit', $campeonato) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('campeonatos.destroy', $campeonato) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Eliminar campeonato?')">
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
