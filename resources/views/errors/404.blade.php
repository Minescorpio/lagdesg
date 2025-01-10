@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="max-w-xl w-full px-4">
        <div class="text-center">
            <div class="text-6xl font-bold text-blue-600 mb-4">404</div>
            <h1 class="text-2xl font-semibold text-white mb-4">{{ __('Page not found') }}</h1>
            <p class="text-gray-400 mb-8">{{ __('Sorry, we couldn\'t find the page you\'re looking for.') }}</p>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                {{ __('Back to Dashboard') }}
            </a>
        </div>
    </div>
</div>
@endsection 