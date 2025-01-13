@extends('components.layouts.shop')

@section('content')
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-[#2E324A]/80 backdrop-blur-sm shadow-md overflow-hidden rounded-lg">
            <h2 class="text-2xl font-bold text-center text-white mb-6">{{ __('Customer Login') }}</h2>

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-400">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('shop.login') }}">
                @csrf

                <div>
                    <label for="email" class="block font-medium text-sm text-gray-300">{{ __('Email') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                           class="mt-1 block w-full border-gray-700 bg-[#1B1D29]/50 text-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                    @error('email')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="password" class="block font-medium text-sm text-gray-300">{{ __('Password') }}</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="mt-1 block w-full border-gray-700 bg-[#1B1D29]/50 text-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                    @error('password')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                               class="rounded border-gray-700 bg-[#1B1D29]/50 text-blue-500 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-300">{{ __('Remember me') }}</span>
                    </label>

                    @if (Route::has('shop.password.request'))
                        <a class="text-sm text-gray-300 hover:text-white" href="{{ route('shop.password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <div class="flex flex-col space-y-4 mt-6">
                    <button type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300">
                        {{ __('Log in') }}
                    </button>

                    <a href="{{ route('shop.register') }}"
                       class="w-full inline-flex justify-center items-center px-4 py-2 bg-[#1B1D29]/50 border border-gray-700 rounded-md font-semibold text-sm text-gray-300 hover:text-white hover:border-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300">
                        {{ __('Create an account') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection 