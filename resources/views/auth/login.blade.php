<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#141728]">
        <div class="w-full sm:max-w-md px-6 py-4 bg-[#1F2337] shadow-md overflow-hidden sm:rounded-lg">
            <!-- Logo -->
            <div class="mb-8 flex justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="La Grotte des Geeks" class="w-48">
            </div>

            <h2 class="text-2xl font-bold text-white mb-6 text-center">{{ __('Connexion') }}</h2>

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-label for="email" :value="__('Email')" class="text-white" />
                    <x-input id="email" class="block mt-1 w-full bg-[#2E324A] border-gray-600 text-white" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-label for="password" :value="__('Mot de passe')" class="text-white" />
                    <x-input id="password" class="block mt-1 w-full bg-[#2E324A] border-gray-600 text-white"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
                </div>

                <!-- Remember Me -->
                <div class="mt-4 flex items-center justify-between">
                    <label for="remember_me" class="flex items-center">
                        <x-checkbox id="remember_me" name="remember" class="bg-[#2E324A] border-gray-600" />
                        <span class="ms-2 text-sm text-white">{{ __('Se souvenir de moi') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-blue-400 hover:text-blue-300" href="{{ route('password.request') }}">
                            {{ __('Mot de passe oublié ?') }}
                        </a>
                    @endif
                </div>

                <div class="flex items-center justify-between mt-4">
                    <a class="text-sm text-blue-400 hover:text-blue-300" href="{{ route('register') }}">
                        {{ __('Créer un compte') }}
                    </a>

                    <x-button class="btn-primary">
                        {{ __('Se connecter') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
