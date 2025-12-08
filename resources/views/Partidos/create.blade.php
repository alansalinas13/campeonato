@extends('layouts.app')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@section('content')
    <div class="container">
        <h2>Crear Partido</h2>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('partidos.store') }}">
            @csrf

            <div class="mb-3">
                <label>Ronda</label>
                <select name="parrond" class="form-control" required>
                    @foreach(\App\Models\Partido::rondas() as $key => $nombre)
                        <option value="{{ $key }}">{{ $nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Día y hora</label>
                <input type="datetime-local" name="parfec" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Fecha</label>
                <input type="number" name="parfechas" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Club Local</label>
                <select name="idclub_local" class="form-control" required>
                    <option value="">-- Seleccionar --</option>
                    @foreach($clubes as $club)
                        <option value="{{ $club->idclub }}">{{ $club->clubnom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Club Visitante</label>
                <select name="idclub_visitante" class="form-control" required>
                    <option value="">-- Seleccionar --</option>
                    @foreach($clubes as $club)
                        <option value="{{ $club->idclub }}">{{ $club->clubnom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Goles Local</label>
                <input type="number" name="pargolloc" class="form-control" value="0" required>
            </div>

            <div class="mb-3">
                <label>Goles Visitante</label>
                <input type="number" name="pargolvis" class="form-control" value="0" required>
            </div>

            <div class="mb-3">
                <label>¿Hubo penales?</label>
                <select name="parpen" class="form-control" required>
                    <option value="0">No</option>
                    <option value="1">Sí</option>
                </select>
            </div>

            <div class="mb-3">
                <!--<label>Ganador</label>
            <select name="idclub_ganador" class="form-control">
                <option value="">-- Sin jugar --</option>
                <option value="0">Empate</option>
                @foreach($clubes as $club)
                    <option value="{{ $club->idclub }}">{{ $club->clubnom }}</option>

                @endforeach
                </select>-->
                <label>Ganador (opcional)</label>
                <select name="idclub_ganador" class="form-control">
                    <option value="">-- Sin jugar --</option>
                    <option
                        value="-1" {{ old('idclub_ganador', $partido->idclub_ganador ?? '') == -1 ? 'selected' : '' }}>
                        Empate
                    </option>
                    @php
                        $local = $partido->idclub_local ?? old('idclub_local');
                        $visitante = $partido->idclub_visitante ?? old('idclub_visitante');
                    @endphp
                    @if($local)
                        <option
                            value="{{ $local }}" {{ old('idclub_ganador', $partido->idclub_ganador ?? '') == $local ? 'selected' : '' }}>
                            {{ $clubes->firstWhere('idclub', $local)?->clubnom ?? 'Club Local' }}
                        </option>
                    @endif
                    @if($visitante)
                        <option
                            value="{{ $visitante }}" {{ old('idclub_ganador', $partido->idclub_ganador ?? '') == $visitante ? 'selected' : '' }}>
                            {{ $clubes->firstWhere('idclub', $visitante)?->clubnom ?? 'Club Visitante' }}
                        </option>
                    @endif
                </select>
            </div>

            <div class="mb-3">
                <label>Campeonato</label>
                <select name="idcampeonato" class="form-control" required>
                    <option value="">-- Seleccionar --</option>
                    @foreach($campeonatos as $camp)
                        <option value="{{ $camp->idcampeonato }}">{{ $camp->campnom }}</option>
                    @endforeach
                </select>
            </div>
            <h5 class="mt-4">Eventos del Partido</h5>

            <table class="table table-sm" id="tabla-eventos">
                <thead>
                <tr>
                    <th>Minuto</th>
                    <th>Jugador</th>
                    <th>Descripción</th>
                    <th>Acción</th>
                </tr>
                </thead>
                <tbody>
                <!-- Eventos se cargarán dinámicamente con JS -->
                </tbody>
            </table>

            <button type="button" class="btn btn-sm btn-outline-primary" onclick="agregarEvento()">+ Agregar Evento
            </button>

            <input type="hidden" name="eventos_json" id="eventos_json">

            <button type="submit" class="btn btn-success">Crear</button>
            <a href="{{ route('partidos.index') }}"
               class="btn btn-secondary"
               style="background:#6c757d; border:none;">
                Cancelar
            </a>
        </form>
    </div>
@endsection


<script>
    let eventos = [];

    function agregarEvento(evento = {}) {
        const jugadores = @json($jugadores); // pasá la lista desde el controlador
        const tbody = document.querySelector('#tabla-eventos tbody');
        const index = eventos.length;

        const row = document.createElement('tr');

        row.innerHTML = `
            <td><input type="number" name="evento[${index}][minuto]" class="form-control" value="${evento.minuto || ''}" required></td>
            <td>
                <select name="evento[${index}][jugador]" class="form-control" required>
                    <option value="">-- Jugador --</option>
                    ${jugadores.map(j => `<option value="${j.idjugador}" ${evento.jugador == j.idjugador ? 'selected' : ''}>${j.jugnom}</option>`).join('')}
                </select>
            </td>
            <td>
                <select name="evento[${index}][descripcion]" class="form-control" required>
                    <option value="Gol" ${evento.descripcion == 'Gol' ? 'selected' : ''}>Gol</option>
                    <option value="Tarjeta Amarilla" ${evento.descripcion == 'Tarjeta Amarilla' ? 'selected' : ''}>Tarjeta Amarilla</option>
                    <option value="Tarjeta Roja" ${evento.descripcion == 'Tarjeta Roja' ? 'selected' : ''}>Tarjeta Roja</option>
                </select>
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-danger" onclick="eliminarFila(this)">✖</button>
            </td>
        `;

        tbody.appendChild(row);
        eventos.push(evento);
    }

    function eliminarFila(btn) {
        const row = btn.closest('tr');
        row.remove();
    }

</script>
