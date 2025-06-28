@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Usuario</h2>

    <form method="POST" action="{{ route('usuarios.update', $usuario) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $usuario->name) }}" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $usuario->email) }}" required>
        </div>

        <div class="mb-3">
            <label>Contraseña (dejar vacío si no se cambia)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="mb-3">
            <label>Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <div class="mb-3">
            <label>Rol</label>
            <select name="role" class="form-control" required>
                <option value="1" {{ $usuario->role == 1 ? 'selected' : '' }}>Administrador</option>
                <option value="2" {{ $usuario->role == 2 ? 'selected' : '' }}>Dirigente</option>
                <option value="3" {{ $usuario->role == 3 ? 'selected' : '' }}>Invitado</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Club (opcional)</label>
            <select name="idclub" class="form-control">
                <option value="">-- Ninguno --</option>
                @foreach($clubes as $club)
                    <option value="{{ $club->idclub }}" {{ $usuario->idclub == $club->idclub ? 'selected' : '' }}>
                        {{ $club->clubnom }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Actualizar</button>
    </form>
</div>
@endsection
