@extends('layouts.app')

@section('content')
    <div class="container">
        <form method="GET" action="{{ route('posiciones.index') }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label>Seleccionar Campeonato:</label>
                    <select name="idcampeonato" class="form-control" onchange="this.form.submit()">
                        @foreach($campeonatos as $camp)
                            <option
                                value="{{ $camp->idcampeonato }}" {{ $camp->idcampeonato == $idcampeonato ? 'selected' : '' }}>
                                {{ $camp->campnom }} ({{ $camp->canpanio }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            @if(Auth::user()->role === 1)
                <a href="{{ route('campeonatos.generarCuartos', $idcampeonato) }}"
                   class="btn btn-sm btn-outline-primary mb-3">
                    Generar Cuartos de Final
                </a>
                <a href="{{ route('campeonatos.generarEliminatorias', $idcampeonato) }}"
                   class="btn btn-sm btn-outline-danger mb-3">
                    Generar Semifinales y Final
                </a>
                <a href="{{ route('campeonatos.asignarFinalistas', $idcampeonato) }}"
                   class="btn btn-sm btn-outline-success mb-3">
                    Asignar Finalistas
                </a>
            @endif
        </form>

        <h2>Tabla de Posiciones - Grupo A</h2>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Club</th>
                <th>PJ</th>
                <th>G</th>
                <th>E</th>
                <th>P</th>
                <th>GF</th>
                <th>GC</th>
                <th>Pts</th>
            </tr>
            </thead>
            <tbody>
            @foreach($grupoA as $row)
                <tr>
                    <td>{{ $row->club->clubnom }}</td>
                    <td>{{ $row->estparjug }}</td>
                    <td>{{ $row->estpargan }}</td>
                    <td>{{ $row->estparemp }}</td>
                    <td>{{ $row->estparper }}</td>
                    <td>{{ $row->estgolafav }}</td>
                    <td>{{ $row->estgolencont }}</td>
                    <td>{{ $row->estpuntos }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <h2 class="mt-5">Tabla de Posiciones - Grupo B</h2>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Club</th>
                <th>PJ</th>
                <th>G</th>
                <th>E</th>
                <th>P</th>
                <th>GF</th>
                <th>GC</th>
                <th>Pts</th>
            </tr>
            </thead>
            <tbody>
            @foreach($grupoB as $row)
                <tr>
                    <td>{{ $row->club->clubnom }}</td>
                    <td>{{ $row->estparjug }}</td>
                    <td>{{ $row->estpargan }}</td>
                    <td>{{ $row->estparemp }}</td>
                    <td>{{ $row->estparper }}</td>
                    <td>{{ $row->estgolafav }}</td>
                    <td>{{ $row->estgolencont }}</td>
                    <td>{{ $row->estpuntos }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
