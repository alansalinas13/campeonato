@extends('layouts.app')

@section('content')

    <div class="container">
        <h2>Jugadores de {{ $club->clubnom }}</h2>

        @if($club->jugadores->count())
            <table class="table table-bordered">

                <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Cédula</th>
                    <th>Estado</th>
                    <th>Fecha Habilitación</th>
                    <th>Amarillas</th>
                    <th>Suspendido</th>
                    <th>Partidos Suspendido</th>
                    <th>Goles</th>
                    <th>Restablecer Amarillas y Sanciones</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach($club->jugadores as $jugador)
                    @php
                        $color = '';
                        if ($jugador->jugest == 2 && $jugador->jugfechab) {
                            $diasRestantes = \Carbon\Carbon::parse($jugador->jugfechab)->addDays(30)->diffInDays(now(), false);
                            if ($diasRestantes <= -5) {
                                $color = 'table-warning'; // naranja
                            } elseif ($diasRestantes > 0) {
                                $color = 'table-danger'; // rojo
                            }
                        }else if ($jugador->jugest == 1){// si esta directamente deshbilitado
                            $color = 'table-danger'; // rojo
                        }

                    @endphp
                    <tr class="{{ $color }}">
                        <td>{{ $jugador->jugnom }}</td>
                        <td>{{ $jugador->jugcedula }}</td>
                        <td>
                            @if($jugador->jugest == 1)
                                Inhabilitado
                            @elseif($jugador->jugest == 2)
                                Habilitado en Liga
                            @elseif($jugador->jugest == 3)
                                Habilitado en UFI
                            @endif
                        </td>
                        <td>{{ $jugador->jugfechab ?? '-' }}</td>
                        <td>{{ $jugador->jugtaranar }}</td>
                        <td>{{ $jugador->jugsusp ? 'Sí' : 'No' }}</td>
                        {{--<td>{{ $jugador->club?->clubnom ?? '---' }}</td>--}}
                        <td>{{ $jugador->jugpart_susp }}</td>
                        <td>{{ $jugador->juggoles }}</td>
                        <td>
                            <form action="{{ route('jugadores.restablecer', $jugador->idjugador) }}" method="POST"
                                  onsubmit="return confirm('¿Seguro que deseas restablecer tarjetas y suspensión?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-warning">🔄 Restablecer</button>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('jugadores.edit', $jugador) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('jugadores.destroy', $jugador) }}" method="POST"
                                  style="display:inline-block;" onsubmit="return confirm('¿Eliminar jugador?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p>No hay jugadores registrados.</p>
        @endif

        <a href="{{ route('jugadores.index') }}" class="btn btn-secondary">← Volver</a>
    </div>
@endsection
