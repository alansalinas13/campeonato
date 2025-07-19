@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Crear Club</h2>
        <form action="{{ route('clubes.store') }}" method="POST" enctype="multipart/form-data">
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
                <input type="file" name="clublogo" class="form-control" accept="image/*" onchange="previewLogo(event)">
            </div>

            <div id="preview-container" class="mb-3" style="display: none;">
                <label>Vista previa del logo:</label><br>
                <img id="logo-preview" src="" alt="Vista previa del logo" style="max-width: 150px; max-height: 150px;">
            </div>

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
<script>
    function previewLogo(event) {
        const input = event.target;
        const preview = document.getElementById('logo-preview');
        const container = document.getElementById('preview-container');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                container.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            container.style.display = 'none';
        }
    }
</script>

