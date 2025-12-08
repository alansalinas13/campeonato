@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Crear Usuario</h2>
    <form method="POST" action="{{ route('usuarios.store') }}">
        @csrf

        <div class="mb-3">
            <label for="name">Nombre</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label for="email">Correo electrónico</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
        </div>

        <div class="mb-3">
            <label for="password">Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation">Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="role">Rol</label>
            <select name="role" class="form-control" required>
                <option value="1">Administrador</option>
                <option value="2">Dirigente</option>
                <option value="3" selected>Invitado</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="idclub">Club (opcional)</label>
            <select name="idclub" class="form-control">
                <option value="">-- Ninguno --</option>
                @foreach ($clubes as $club)
                    <option value="{{ $club->idclub }}">{{ $club->clubnom }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Crear</button>
        <a href="{{ route('usuarios.index') }}"
           class="btn btn-secondary"
           style="background:#6c757d; border:none;">
            Cancelar
        </a>
    </form>
</div>
@endsection
