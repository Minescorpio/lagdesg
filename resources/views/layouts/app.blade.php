<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'La Grotte des Geeks') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-[#1A1B26] text-white">
        <x-banner />

        <div class="min-h-screen">
            <!-- Navigation -->
            <nav class="gradient-primary border-b border-gray-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center hover-glow">
                                <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-white">
                                    {{ config('app.name') }}
                                </a>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" class="text-white hover:text-[#FFD700]">
                                    {{ __('Tableau de bord') }}
                                </x-nav-link>
                                <x-nav-link href="{{ route('pos.terminal') }}" :active="request()->routeIs('pos.terminal')" class="text-white hover:text-[#FFD700]">
                                    {{ __('Terminal de vente') }}
                                </x-nav-link>
                                <x-nav-link href="{{ route('products.index') }}" :active="request()->routeIs('products.*')" class="text-white hover:text-[#FFD700]">
                                    {{ __('Produits') }}
                                </x-nav-link>
                                <x-nav-link href="{{ route('categories.index') }}" :active="request()->routeIs('categories.*')" class="text-white hover:text-[#FFD700]">
                                    {{ __('Catégories') }}
                                </x-nav-link>
                                <x-nav-link href="{{ route('customers.index') }}" :active="request()->routeIs('customers.*')" class="text-white hover:text-[#FFD700]">
                                    {{ __('Clients') }}
                                </x-nav-link>
                                <x-nav-link href="{{ route('sales.index') }}" :active="request()->routeIs('sales.*')" class="text-white hover:text-[#FFD700]">
                                    {{ __('Ventes') }}
                                </x-nav-link>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button class="flex items-center text-sm font-medium text-white hover:text-[#FFD700] hover:border-[#FFD700] focus:outline-none focus:text-[#FFD700] focus:border-[#FFD700] transition duration-150 ease-in-out">
                                            <div>{{ Auth::user()->name }}</div>

                                            <div class="ml-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <!-- Account Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Gérer le compte') }}
                                        </div>

                                        <x-dropdown-link href="{{ route('profile.show') }}" class="text-gray-200 hover:text-[#FFD700]">
                                            {{ __('Profil') }}
                                        </x-dropdown-link>

                                        <div class="border-t border-gray-700"></div>

                                        <!-- Authentication -->
                                        <form method="POST" action="{{ route('logout') }}" x-data>
                                            @csrf

                                            <x-dropdown-link href="{{ route('logout') }}"
                                                        @click.prevent="$root.submit();" class="text-gray-200 hover:text-[#FFD700]">
                                                {{ __('Déconnexion') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-[#FFD700] hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-[#FFD700] transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-[#24273A] shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
