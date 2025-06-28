@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Club</h2>
    <form action="{{ route('clubes.update', $clube->idclub) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="clubnom" value="{{ old('clubnom', $clube->clubnom) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Descripción</label>
            <textarea type="text" name="clubdescri" class="form-control"> {{ old('clubdescri', $clube->clubdescri) }} </textarea>
        </div>

        <div class="mb-3">
            <label>Logo (url o nombre de archivo)</label>
            <input type="text" name="clublogo" value="{{ old('clublogo', $clube->clublogo) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Grupo</label>
            <select name="clubgroup"  class="form-control">
                <option value="">-- Sin grupo --</option>
                <option value="A" {{ old('clubgroup', $clube->clubgroup) == 'A' ? 'selected' : '' }}>Grupo A</option>
                <option value="B" {{ old('clubgroup', $clube->clubgroup) == 'B' ? 'selected' : '' }}>Grupo B</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Editar</button>
    </form>
</div>
@endsection
