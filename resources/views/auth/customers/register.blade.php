@extends('components.layouts.shop')

@section('content')
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-[#2E324A]/80 backdrop-blur-sm shadow-md overflow-hidden rounded-lg">
            <h2 class="text-2xl font-bold text-center text-white mb-6">{{ __('Create Customer Account') }}</h2>

            <form method="POST" action="{{ route('shop.register') }}">
                @csrf

                <div>
                    <label for="name" class="block font-medium text-sm text-gray-300">{{ __('Name') }}</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                           class="mt-1 block w-full border-gray-700 bg-[#1B1D29]/50 text-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                    @error('name')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="email" class="block font-medium text-sm text-gray-300">{{ __('Email') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                           class="mt-1 block w-full border-gray-700 bg-[#1B1D29]/50 text-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                    @error('email')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="password" class="block font-medium text-sm text-gray-300">{{ __('Password') }}</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                           class="mt-1 block w-full border-gray-700 bg-[#1B1D29]/50 text-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                    @error('password')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="password_confirmation" class="block font-medium text-sm text-gray-300">{{ __('Confirm Password') }}</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                           class="mt-1 block w-full border-gray-700 bg-[#1B1D29]/50 text-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                </div>

                <div class="flex flex-col space-y-4 mt-6">
                    <button type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300">
                        {{ __('Register') }}
                    </button>

                    <a href="{{ route('shop.login') }}"
                       class="w-full inline-flex justify-center items-center px-4 py-2 bg-[#1B1D29]/50 border border-gray-700 rounded-md font-semibold text-sm text-gray-300 hover:text-white hover:border-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300">
                        {{ __('Already have an account?') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection 