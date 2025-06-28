@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Jugador</h2>

    <form action="{{ route('jugadores.update', $jugador->idjugador) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="jugnom" class="form-control" value="{{ old('jugnom', $jugador->jugnom) }}" required>
        </div>

        <div class="mb-3">
            <label>Estado</label>
            <input type="number" name="jugest" class="form-control" value="{{ old('jugest', $jugador->jugest) }}" required>
        </div>

        <div class="mb-3">
            <label>Tarjetas Amarillas</label>
            <input type="number" name="jugtaranar" class="form-control" value="{{ old('jugtaranar', $jugador->jugtaranar) }}" required>
        </div>

        <div class="mb-3">
            <label>Tarjetas Rojas</label>
            <input type="number" name="jugtarroj" class="form-control" value="{{ old('jugtarroj', $jugador->jugtarroj) }}" required>
        </div>

        <div class="mb-3">
            <label>¿Suspendido?</label>
            <select name="jugsusp" class="form-control">
                <option value="0" {{ $jugador->jugsusp == 0 ? 'selected' : '' }}>No</option>
                <option value="1" {{ $jugador->jugsusp == 1 ? 'selected' : '' }}>Sí</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Club (opcional)</label>
            <select name="idclub" class="form-control">
                <option value="">-- Ninguno --</option>
                @foreach($clubes as $club)
                    <option value="{{ $club->idclub }}" {{ $jugador->idclub == $club->idclub ? 'selected' : '' }}>
                        {{ $club->clubnom }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>
@endsection
