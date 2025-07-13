@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Editar Jugador</h2>

        <form action="{{ route('jugadores.update', $jugador->idjugador) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Nombre</label>
                <input type="text" name="jugnom" class="form-control" value="{{ old('jugnom', $jugador->jugnom) }}"
                       required>
            </div>

            <div class="mb-3">
                <label>Cedula</label>
                <input type="text" name="jugcedula" class="form-control" value="{{ old('jugcedula', $jugador->jugcedula) }}"
                       required>
            </div>

            <div class="mb-3">
                <label>Estado</label>
                <select name="jugest" id="jugest" class="form-control" onchange="toggleFechaHabilitacion()">
                    <option value="1" {{ $jugador->jugest == 1 ? 'selected' : '' }}>Inhabilitado</option>
                    <option value="2" {{ $jugador->jugest == 2 ? 'selected' : '' }}>Habilitado en Liga</option>
                    <option value="3" {{ $jugador->jugest == 3 ? 'selected' : '' }}>Habilitado en UFI</option>
                </select>
            </div>
            <div id="fechaHabilitacion" style="display:none;">
                <label>Fecha Habilitacion</label>
                <input type="date" name="jugfechab" class="form-control" value="{{ old('jugfechab', $jugador->jugfechab) }}">
            </div>
            <div class="mb-3">
                <label>Tarjetas Amarillas</label>
                <input type="number" name="jugtaranar" class="form-control"
                       value="{{ old('jugtaranar', $jugador->jugtaranar) }}" required>
            </div>

            <div class="mb-3">
                <label>¿Suspendido?</label>
                <select name="jugsusp" id="jugsusp" class="form-control" onchange="togglePartidosSusp()">
                    <option value="0" {{ $jugador->jugsusp == 0 ? 'selected' : '' }}>No</option>
                    <option value="1" {{ $jugador->jugsusp == 1 ? 'selected' : '' }}>Sí</option>
                </select>
            </div>

            <div class="mb-3" id="campoPartidosSusp" style="display:none;">
                <label>Cantidad de Partidos Suspendidos</label>
                <input type="number" name="jugpart_susp" class="form-control" value="{{ old('jugpart_susp', $jugador->jugpart_susp) }}">
            </div>

            <div class="mb-3">
                <label>Cantidad Goles</label>
                <input type="number" name="juggoles" class="form-control" value="{{ old('juggoles', $jugador->juggoles) }}">
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

    <script>

        function toggleFechaHabilitacion() {
            debugger;

            const estado = document.getElementById('jugest').value;
            document.getElementById('fechaHabilitacion').style.display = (estado == 2) ? 'block' : 'none';
        }

        function togglePartidosSusp() {
            debugger;
            const suspendido = document.getElementById('jugsusp').value;
            document.getElementById('campoPartidosSusp').style.display = (suspendido == 1) ? 'block' : 'none';
        }
    </script>
@endsection
