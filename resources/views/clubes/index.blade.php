@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Clubes</h2>
        @if(Auth::user()->role === 1)
            <a href="{{ route('clubes.create') }}" class="btn btn-primary mb-3">Crear Club</a>
        @endif

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Grupo</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($clubes as $club)
                <tr>
                    <td>{{ $club->clubnom }}</td>
                    <td>{{ $club->clubgroup }}</td>
                    <td>{{ $club->clubdescri }}</td>
                    <td>
                        <a href="{{ route('clubes.edit', $club) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('clubes.destroy', $club) }}" method="POST" style="display:inline-block;"
                              onsubmit="return confirm('¿Eliminar este club?')">
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
