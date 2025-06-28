<x-app-layout>
    <p>Bienvenido, {{ Auth::user()->name }} (Rol: {{ Auth::user()->role }})</p>
    @if(Auth::user()->role === 1)
    <a href="{{ route('usuarios.index') }}">Usuarios</a>
    <a href="{{ route('clubes.index') }}">Clubes</a>
    @endif
    
    @if(Auth::user()->role === 2)
        <a href="{{ route('jugadores.dirigente') }}">Mis Jugadores</a>
    @endif
    
    @if(Auth::user()->role === 3)
        <a href="{{ route('tabla.invitado') }}">Tabla de posiciones</a>
    @endif

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
