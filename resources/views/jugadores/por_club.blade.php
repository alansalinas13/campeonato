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
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalFicha"
                                    data-id="{{ $jugador->idjugador }}">
                                Agregar Ficha
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <!-- Modal -->
            <div class="modal fade" id="modalFicha" tabindex="-1" aria-labelledby="modalFichaLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form id="formFicha" method="POST" action="{{ route('fichas.store') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="idjugador" id="idjugador">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Agregar Ficha del Jugador</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Año</label>
                                    <input type="number" name="anio" class="form-control" value="{{ date('Y') }}"
                                           required>
                                </div>
                                <div class="mb-3">
                                    <label>Tipo de Habilitación</label>
                                    <select name="tipo_habilitacion" class="form-control" required>
                                        <option value="2">Habilitación en Liga</option>
                                        <option value="3">Habilitación en UFI</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Imagen de la ficha</label>
                                    <input type="file" name="imagen_ficha" accept="image/*" class="form-control"
                                           required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-success" type="submit">Guardar</button>
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <script>
                const modalFicha = document.getElementById('modalFicha');
                modalFicha.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    debugger;
                    document.getElementById('idjugador').value = button.getAttribute('data-id');
                });
            </script>
        @else
            <p>No hay jugadores registrados.</p>
        @endif

        <a href="{{ route('jugadores.index') }}" class="btn btn-secondary">← Volver</a>
    </div>
@endsection


