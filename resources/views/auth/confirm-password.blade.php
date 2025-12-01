{{--
<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button>
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
--}}


<x-guest-layout>

    <h2 class="mb-4">Confirmar contraseña</h2>

    <p class="text-sm text-gray-600 mb-4">
        Esta es una zona protegida de la aplicación. Por favor, confirmá tu contraseña para continuar.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="form-row">
            <x-input-label for="password" :value="__('Contraseña')" />
            <x-text-input id="password" name="password" type="password"
                          required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="form-actions">
            <a href="{{ route('login') }}" class="btn-ghost text-sm">
                Cancelar
            </a>

            <x-primary-button>
                {{ __('Confirmar') }}
            </x-primary-button>
        </div>
    </form>

</x-guest-layout>
