<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Errores - Ficha Jugador</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2 {
            color: red;
        }
    </style>
</head>
<body>
<h2>Se encontraron errores al analizar la ficha</h2>
<ul>
    @foreach ($errores as $error)
        <li>{{ $error }}</li>
    @endforeach
</ul>
</body>
</html>
