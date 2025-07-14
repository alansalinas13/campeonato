@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Crear Club</h2>
        <form action="{{ route('clubes.store') }}" method="POST" nctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label>Nombre</label>
                <input type="text" name="clubnom" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Descripción</label>
                <textarea name="clubdescri" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label>Logo (url o nombre de archivo)</label>
                {{--<input type="text" name="clublogo" class="form-control">--}}
                <input type="file" name="clublogo" class="form-control" accept="image/*">

                <div class="mb-3">
                    <label>Grupo</label>
                    <select name="clubgroup" class="form-control">
                        <option value="">-- Sin grupo --</option>
                        <option value="A">Grupo A</option>
                        <option value="B">Grupo B</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Crear</button>
        </form>
    </div>
@endsection
