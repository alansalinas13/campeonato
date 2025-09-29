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
            <!-- resources/views/jugadores/modal_ficha.blade.php -->
            <div class="modal fade" id="modalFichaJugador" tabindex="-1" role="dialog" aria-labelledby="modalFichaJugadorLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalFichaJugadorLabel">Agregar Ficha / Fichaje</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="fichaForm" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="idjugador" id="inputJugadorId">

                                <div class="mb-3">
                                    <label for="tipo_fichaje">Tipo de fichaje</label>
                                    <select name="tipo_fichaje" id="tipo_fichaje" class="form-control" required>
                                        <option value="">-- Seleccione --</option>
                                        <option value="primer_menor">Primer fichaje (menor de edad)</option>
                                        <option value="primer_mayor">Primer fichaje (mayor de edad)</option>
                                        <option value="traspaso">Traspaso</option>
                                    </select>
                                </div>

                                <div class="mb-3" id="campoTipTraspaso">
                                    <label for="tipo_traspaso">Liga origen</label>
                                    <select name="tipo_traspaso" id="tipo_traspaso" class="form-control" required>
                                        <option value="">-- Seleccione --</option>
                                        <option value="liga_local">Liga Local</option>
                                        <option value="otra_liga">Otra Liga </option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label>Ficha verde</label>
                                    <input type="file" name="ficha_verde" class="form-control" accept="image/*" required>
                                </div>

                                <div class="mb-3">
                                    <label>Fotocopia de cédula</label>
                                    <input type="file" name="fotocopia_cedula" class="form-control" accept="image/*" required>
                                </div>

                                <div class="mb-3">
                                    <label>Ficha médica</label>
                                    <input type="file" name="ficha_medica" class="form-control" accept="image/*" required>
                                </div>

                                <!-- Campo condicional -->
                                <div class="mb-3 d-none" id="campoAutorizacion">
                                    <label>Autorización del menor</label>
                                    <input type="file" name="autorizacion_menor" id="autorizacion_menor" class="form-control" accept="image/*">
                                </div>

                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                // 🔹 Lógica para mostrar/ocultar autorización del menor
                document.getElementById('tipo_fichaje').addEventListener('change', function() {
                    const campoAutorizacion = document.getElementById('campoAutorizacion');
                    const inputAutorizacion = document.getElementById('autorizacion_menor');
                    const campoTipTraspaso = document.getElementById('campoTipTraspaso');
                    const inputTipTraspaso = document.getElementById('tipo_traspaso');
                    campoTipTraspaso.classList.add('d-none');
                    inputTipTraspaso.removeAttribute('required');
                    if (this.value === 'primer_menor') {
                        campoAutorizacion.classList.remove('d-none');
                        inputAutorizacion.setAttribute('required', 'required');
                    } else {
                        campoAutorizacion.classList.add('d-none');
                        inputAutorizacion.removeAttribute('required');
                        inputAutorizacion.value = ''; // limpia si estaba cargado
                        if(this.value === 'traspaso') {
                            campoTipTraspaso.classList.remove('d-none');
                            inputTipTraspaso.setAttribute('required', 'required');
                        }
                    }
                });

                // 🔹  lógica de envío con progreso y pdf
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
                    const campoAutorizacion = document.getElementById('campoAutorizacion');
                    const inputAutorizacion = document.getElementById('autorizacion_menor');
                    const campoTipTraspaso = document.getElementById('campoTipTraspaso');
                    const inputTipTraspaso = document.getElementById('tipo_traspaso');
                    campoAutorizacion.classList.add('d-none');
                    inputAutorizacion.removeAttribute('required');
                    inputAutorizacion.value = ''; // limpia si estaba cargado
                    campoTipTraspaso.classList.add('d-none');
                    inputTipTraspaso.removeAttribute('required');
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


