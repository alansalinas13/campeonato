{{--
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                   @yield('content')
            </main>
        </div>
    </body>
</html>
--}}

    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Campeonato</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        .sidebar {
            height: 100vh;
            background-color: #3694dd;
            padding-top: 1rem;
        }

        .sidebar a {
            color: white;
            padding: 0.75rem 1rem;
            display: block;
            text-decoration: none;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #2d7cc0;
        }

        .main-content {
            margin-left: 220px;
            padding: 2rem;
        }
    </style>
</head>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar position-fixed top-0 start-0">
        <h5 class="text-white text-center mb-4">Menú</h5>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('usuarios.index') }}" class="{{ request()->is('usuarios*') ? 'active' : '' }}">Usuarios</a>
        <a href="{{ route('clubes.index') }}" class="{{ request()->is('clubes*') ? 'active' : '' }}">Clubes</a>
        <a href="{{ route('campeonatos.index') }}" class="{{ request()->is('campeonatos*') ? 'active' : '' }}">Campeonatos</a>
        <a href="{{ route('jugadores.index') }}" class="{{ request()->is('jugadores*') ? 'active' : '' }}">Jugadores</a>
        <a href="{{ route('partidos.index') }}" class="{{ request()->is('partidos*') ? 'active' : '' }}">Partidos</a>
        <a href="{{ route('posiciones.index', ['idcampeonato' => now()->year]) }}"
           class="{{ request()->is('posiciones*') ? 'active' : '' }}">Posiciones</a>
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Salir</a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </div>

    <!-- Contenido principal -->
    <div class="main-content w-100">
        @yield('content')
    </div>
</div>

</body>
</html>

