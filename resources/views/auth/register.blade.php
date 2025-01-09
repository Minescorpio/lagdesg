<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#141728]">
        <div class="w-full sm:max-w-md px-6 py-4 bg-[#1F2337] shadow-md overflow-hidden sm:rounded-lg">
            <!-- Logo -->
            <div class="mb-8 flex justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="La Grotte des Geeks" class="w-48">
            </div>

            <h2 class="text-2xl font-bold text-white mb-6 text-center">{{ __('Créer un compte') }}</h2>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <x-label for="name" :value="__('Nom')" class="text-white" />
                    <x-input id="name" class="block mt-1 w-full bg-[#2E324A] border-gray-600 text-white" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-label for="email" :value="__('Email')" class="text-white" />
                    <x-input id="email" class="block mt-1 w-full bg-[#2E324A] border-gray-600 text-white" type="email" name="email" :value="old('email')" required autocomplete="username" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-label for="password" :value="__('Mot de passe')" class="text-white" />
                    <x-input id="password" class="block mt-1 w-full bg-[#2E324A] border-gray-600 text-white"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-label for="password_confirmation" :value="__('Confirmer le mot de passe')" class="text-white" />
                    <x-input id="password_confirmation" class="block mt-1 w-full bg-[#2E324A] border-gray-600 text-white"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
                </div>

                <div class="flex items-center justify-between mt-4">
                    <a class="text-sm text-blue-400 hover:text-blue-300" href="{{ route('login') }}">
                        {{ __('Déjà inscrit ?') }}
                    </a>

                    <x-button class="btn-primary">
                        {{ __('S\'inscrire') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
