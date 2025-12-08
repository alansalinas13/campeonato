@extends('layouts.app')
@section('content')
    <div class="container">
        <h2>Editar Club</h2>
        <form action="{{ route('clubes.update', $clube->idclub) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label>Nombre</label>
                <input type="text" name="clubnom" value="{{ old('clubnom', $clube->clubnom) }}" class="form-control"
                       required>
            </div>

            <div class="mb-3">
                <label>Descripción</label>
                <textarea type="text" name="clubdescri"
                          class="form-control"> {{ old('clubdescri', $clube->clubdescri) }} </textarea>
            </div>

            <div class="mb-3">
                <label>Logo </label>
                {{--<input type="text" name="clublogo" value="{{ old('clublogo', $clube->clublogo) }}" class="form-control">--}}
                <input type="file" name="clublogo" value="{{ old('clublogo', $clube->clublogo) }}" class="form-control"
                       accept=".jpeg,.jpg,.png,.gif" onchange="previewLogo(event)">
                {{-- Imagen actual --}}
                @if ($clube->clublogo)
                    <div class="mt-2">
                        <label>Logo actual:</label><br>
                        <img src="{{ asset($clube->clublogo) }}" alt="Logo actual"
                             style="max-width: 150px; max-height: 150px;">
                    </div>
                @else
                    <div class="mt-2">Sin logo actual</div>
                @endif
            </div>
            {{-- Vista previa nueva imagen --}}
            <div id="preview-container" class="mt-3" style="display: none;">
                <label>Nuevo logo:</label><br>
                <img id="logo-preview" src="" alt="Vista previa del logo" style="max-width: 150px; max-height: 150px;">
            </div>


            <div class="mb-3">
                <label>Grupo</label>
                <select name="clubgroup" class="form-control">
                    <option value="">-- Sin grupo --</option>
                    <option value="A" {{ old('clubgroup', $clube->clubgroup) == 'A' ? 'selected' : '' }}>Grupo A
                    </option>
                    <option value="B" {{ old('clubgroup', $clube->clubgroup) == 'B' ? 'selected' : '' }}>Grupo B
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Editar</button>
            <a href="{{ route('clubes.index') }}"
               class="btn btn-secondary"
               style="background:#6c757d; border:none;">
                Cancelar
            </a>
        </form>
    </div>
@endsection

<script>
    function previewLogo(event) {
        const input = event.target;
        const preview = document.getElementById('logo-preview');
        const container = document.getElementById('preview-container');

        if (input.files && input.files[0]) {
            const exte = input.files[0].name.substr(input.files[0].name.lastIndexOf('.') + 1).toLowerCase();
            const extensiones = ['jpeg', 'jpg', 'png', 'gif'];
            if (!extensiones.includes(exte)) {
                alert('Formato invalido. Debe ser jpeg, jpg, png o gif')
            }
            else {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        else {
            preview.src = '';
            container.style.display = 'none';
        }
    }
</script>


