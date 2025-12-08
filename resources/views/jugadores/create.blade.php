{{--
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Crear Jugador</h2>

    <form action="{{ route('jugadores.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="jugnom" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Estado</label>
            <input type="number" name="jugest" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Tarjetas Amarillas</label>
            <input type="number" name="jugtaranar" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Tarjetas Rojas</label>
            <input type="number" name="jugtarroj" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>¿Suspendido?</label>
            <select name="jugsusp" class="form-control">
                <option value="0">No</option>
                <option value="1">Sí</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Club (opcional)</label>
            <select name="idclub" class="form-control">
                <option value="">-- Ninguno --</option>
                @foreach($clubes as $club)
                    <option value="{{ $club->idclub }}">{{ $club->clubnom }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Crear</button>
    </form>
</div>
@endsection
--}}

@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Agregar Jugador</h2>
        <form action="{{ route('jugadores.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Nombre</label>
                <input type="text" name="jugnom" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Nro. de Cédula</label>
                <input type="text" name="jugcedula" class="form-control">
            </div>

            <div class="mb-3">
                <label>Estado</label>
                <select name="jugest" id="jugest" class="form-control" onchange="toggleFechaHabilitacion()"
                        required>
                    <option value="1">Inhabilitado</option>
                    <option value="2">Habilitado en Liga</option>
                    <option value="3">Habilitado en UFI</option>
                </select>
            </div>

            <div class="mb-3" id="fechaHabilitacion" style="display:none;">
                <label>Fecha de Habilitación (Liga)</label>
                <input type="date" name="jugfechab" class="form-control">
            </div>

            <div class="mb-3">
                <label>Tarjetas Amarillas</label>
                <input type="number" name="jugtaranar" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Club</label>
                <select name="idclub" class="form-control" required>
                    @foreach($clubes as $club)
                        <option value="{{ $club->idclub }}">{{ $club->clubnom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Está suspendido?</label>
                <select name="jugsusp" id="jugsusp" class="form-control" onchange="togglePartidosSusp()">
                    <option value="0">No</option>
                    <option value="1">Sí</option>
                </select>
            </div>

            <div class="mb-3" id="campoPartidosSusp" style="display:none;">
                <label>Cantidad de Partidos Suspendidos</label>
                <input type="number" name="jugpart_susp" class="form-control" value="0">
            </div>

            <div class="mb-3" id="campoGoles">
                <label>Cantidad de Goles</label>
                <input type="number" name="juggoles" class="form-control" value="0">
            </div>

            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="{{ route('jugadores.index') }}"
               class="btn btn-secondary"
               style="background:#6c757d; border:none;">
                Cancelar
            </a>
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

