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
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" onclick="abrirModalFicha({{ $jugador->idjugador }})" >
                                Agregar Ficha
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <!-- Modal -->
            <div class="modal fade" id="modalFichaJugador" tabindex="-1" role="dialog"
                 aria-labelledby="modalFichaJugadorLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalFichaJugadorLabel">Habilitar Jugador</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="fichaForm" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="idjugador" id="inputJugadorId">

                                <div class="mb-3">
                                    <label for="anio">Año</label>
                                    <input type="number" name="anio" class="form-control" required min="2000" value="{{ date('Y') }}"
                                           max="{{ date('Y') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="tipo_habilitacion">Tipo de Habilitación</label>
                                    <select name="tipo_habilitacion" class="form-control" required>
                                        <option value="2">Habilitar en Liga</option>
                                        <option value="3">Habilitar en UFI</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label>Ficha (imagen)</label>
                                    <input type="file" name="imagen_ficha" class="form-control" accept="image/*"
                                           required>
                                </div>

                                <div id="progresoContainer" class="progress d-none mb-3">
                                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>

                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                document.getElementById('fichaForm').addEventListener('submit', async function (e) {
                    e.preventDefault();
                    const form = e.target;
                    const formData = new FormData(form);
                    const progressBar = document.querySelector('.progress-bar');
                    const progressContainer = document.getElementById('progresoContainer');

                    progressContainer.classList.remove('d-none');
                    progressBar.style.width = '0%';

                    try {
                        const response = await axios.post("{{ route('fichas.store') }}", formData, {
                            headers: {'Content-Type': 'multipart/form-data'},
                            onUploadProgress: function (progressEvent) {
                                let percent = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                                progressBar.style.width = percent + '%';
                            },
                            responseType: 'blob'
                        });

                        const contentDisposition = response.headers['content-disposition'];
                        if (contentDisposition && contentDisposition.includes('attachment')) {
                            const blob = new Blob([response.data], {type: 'application/pdf'});
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = "reporte_errores_ficha.pdf";
                            a.click();
                            window.URL.revokeObjectURL(url);
                            Swal.fire('Error', 'La ficha tiene errores. Se descargó el informe.', 'error');
                        } else {
                            Swal.fire('Listo', 'Jugador habilitado correctamente.', 'success');
                        }

                        const modal = bootstrap.Modal.getInstance(document.getElementById('modalFichaJugador'));
                        modal.hide();
                        form.reset();
                        progressBar.style.width = '0%';
                        progressContainer.classList.add('d-none');
                    } catch (error) {
                        console.error(error);
                        Swal.fire('Error', 'Ocurrió un error al procesar la ficha.', 'error');
                        progressContainer.classList.add('d-none');
                    }
                });

                function abrirModalFicha(idjugador) {
                    document.getElementById('inputJugadorId').value = idjugador;
                    const modal = new bootstrap.Modal(document.getElementById('modalFichaJugador'));
                    modal.show();
                }
            </script>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @else
            <p>No hay jugadores registrados.</p>
        @endif

        <a href="{{ route('jugadores.index') }}" class="btn btn-secondary">← Volver</a>
    </div>
@endsection


