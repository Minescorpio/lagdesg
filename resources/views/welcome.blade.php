<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }} - Point of Sale System</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-50">
        <!-- Hero Section -->
        <div class="relative overflow-hidden bg-white">
            <div class="mx-auto max-w-7xl">
                <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:pb-28 lg:w-full lg:max-w-2xl">
                    <div class="relative pt-6 px-4 sm:px-6 lg:px-8">
                        <nav class="relative flex items-center justify-between sm:h-10">
                            <div class="flex items-center flex-grow flex-shrink-0 lg:flex-grow-0">
                                <div class="flex items-center justify-between w-full md:w-auto">
                                    <a href="#" class="text-3xl font-bold text-blue-600">
                                        {{ config('app.name') }}
                                    </a>
                                </div>
                            </div>
                            <div class="hidden md:block md:ml-10 md:pr-4 md:space-x-8">
                                @if (Route::has('login'))
                                    <div class="space-x-4">
                                        @auth
                                            <a href="{{ route('dashboard') }}" class="font-medium text-blue-600 hover:text-blue-500">{{ __('Dashboard') }}</a>
                                        @else
                                            <a href="{{ route('login') }}" class="font-medium text-gray-500 hover:text-gray-900">{{ __('Login') }}</a>

                                            @if (Route::has('register'))
                                                <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">{{ __('Register') }}</a>
                                            @endif
                                        @endauth
                                    </div>
                                @endif
                            </div>
                        </nav>
                    </div>

                    <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8">
                        <div class="sm:text-center lg:text-left">
                            <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                                <span class="block">{{ __('Modern Point of Sale') }}</span>
                                <span class="block text-blue-600">{{ __('for Your Business') }}</span>
                            </h1>
                            <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                                Streamline your business operations with our advanced POS system. Manage sales, inventory, and customers all in one place.
                            </p>
                            <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                                <div class="rounded-md shadow">
                                    <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg md:px-10">
                                        {{ __('Get started') }}
                                    </a>
                                </div>
                                <div class="mt-3 sm:mt-0 sm:ml-3">
                                    <a href="#features" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 md:py-4 md:text-lg md:px-10">
                                        {{ __('Learn more') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
            <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
                <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="https://images.unsplash.com/photo-1556740738-b6a63e27c4df?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2850&q=80" alt="Point of Sale System">
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:text-center">
                    <h2 class="text-base text-blue-600 font-semibold tracking-wide uppercase">{{ __('Features') }}</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                        Everything you need to run your business
                    </p>
                    <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                        Our POS system comes with all the features you need to manage your business efficiently.
                    </p>
                </div>

                <div class="mt-10">
                    <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                        <!-- Sales Management -->
                        <div class="relative">
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                <i class="fas fa-cash-register text-xl"></i>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">{{ __('Sales Management') }}</p>
                            <p class="mt-2 ml-16 text-base text-gray-500">
                                {{ __('Process sales quickly and efficiently. Accept multiple payment methods and manage refunds easily.') }}
                            </p>
                        </div>

                        <!-- Inventory Management -->
                        <div class="relative">
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                <i class="fas fa-boxes text-xl"></i>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">{{ __('Inventory Management') }}</p>
                            <p class="mt-2 ml-16 text-base text-gray-500">
                                {{ __('Track stock levels in real-time. Get alerts for low stock and manage multiple locations.') }}
                            </p>
                        </div>

                        <!-- Customer Management -->
                        <div class="relative">
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">{{ __('Customer Management') }}</p>
                            <p class="mt-2 ml-16 text-base text-gray-500">
                                {{ __('Build customer loyalty with profiles, purchase history, and reward programs.') }}
                            </p>
                        </div>

                        <!-- Reporting & Analytics -->
                        <div class="relative">
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                <i class="fas fa-chart-line text-xl"></i>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">{{ __('Reporting & Analytics') }}</p>
                            <p class="mt-2 ml-16 text-base text-gray-500">
                                {{ __('Get insights into your business with detailed reports and real-time analytics.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Testimonials -->
        <div class="bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:text-center">
                    <h2 class="text-base text-blue-600 font-semibold tracking-wide uppercase">{{ __('Testimonials') }}</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                        {{ __('Trusted by businesses everywhere') }}
                    </p>
                </div>
                <div class="mt-10">
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                        <!-- Testimonial 1 -->
                        <div class="bg-white rounded-lg shadow-lg p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-quote-left text-3xl text-blue-500"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-lg font-medium text-gray-900">{{ __('Retail Store Owner') }}</div>
                                    <div class="text-base text-gray-500">{{ __('Retail Store Owner') }}</div>
                                </div>
                            </div>
                            <p class="mt-4 text-gray-500">
                                {{ __('This POS system has transformed how we run our business. The inventory management is exceptional.') }}
                            </p>
                        </div>

                        <!-- Testimonial 2 -->
                        <div class="bg-white rounded-lg shadow-lg p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-quote-left text-3xl text-blue-500"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-lg font-medium text-gray-900">{{ __('Restaurant Manager') }}</div>
                                    <div class="text-base text-gray-500">{{ __('Restaurant Manager') }}</div>
                                </div>
                            </div>
                            <p class="mt-4 text-gray-500">
                                {{ __('Fast, reliable, and easy to use. The customer support is outstanding.') }}
                            </p>
                        </div>

                        <!-- Testimonial 3 -->
                        <div class="bg-white rounded-lg shadow-lg p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-quote-left text-3xl text-blue-500"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-lg font-medium text-gray-900">{{ __('Boutique Owner') }}</div>
                                    <div class="text-base text-gray-500">{{ __('Boutique Owner') }}</div>
                                </div>
                            </div>
                            <p class="mt-4 text-gray-500">
                                {{ __('The reporting features help me make better business decisions. Highly recommended!') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-blue-600">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
                <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                    <span class="block">{{ __('Ready to get started?') }}</span>
                    <span class="block text-blue-200">{{ __('Start your free trial today.') }}</span>
                </h2>
                <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                    <div class="inline-flex rounded-md shadow">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50">
                            {{ __('Get started') }}
                        </a>
                    </div>
                    <div class="ml-3 inline-flex rounded-md shadow">
                        <a href="#" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-700 hover:bg-blue-800">
                            {{ __('Learn more') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
                <div class="grid grid-cols-2 gap-8 md:grid-cols-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">{{ __('Solutions') }}</h3>
                        <ul class="mt-4 space-y-4">
                            <li><a href="#" class="text-base text-gray-300 hover:text-white">{{ __('Point of Sale') }}</a></li>
                            <li><a href="#" class="text-base text-gray-300 hover:text-white">{{ __('Inventory') }}</a></li>
                            <li><a href="#" class="text-base text-gray-300 hover:text-white">{{ __('Customer Management') }}</a></li>
                            <li><a href="#" class="text-base text-gray-300 hover:text-white">{{ __('Analytics') }}</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">{{ __('Support') }}</h3>
                        <ul class="mt-4 space-y-4">
                            <li><a href="#" class="text-base text-gray-300 hover:text-white">{{ __('Documentation') }}</a></li>
                            <li><a href="#" class="text-base text-gray-300 hover:text-white">{{ __('Guides') }}</a></li>
                            <li><a href="#" class="text-base text-gray-300 hover:text-white">{{ __('API Status') }}</a></li>
                            <li><a href="#" class="text-base text-gray-300 hover:text-white">{{ __('Contact') }}</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">{{ __('Company') }}</h3>
                        <ul class="mt-4 space-y-4">
                            <li><a href="#" class="text-base text-gray-300 hover:text-white">{{ __('About') }}</a></li>
                            <li><a href="#" class="text-base text-gray-300 hover:text-white">{{ __('Blog') }}</a></li>
                            <li><a href="#" class="text-base text-gray-300 hover:text-white">{{ __('Careers') }}</a></li>
                            <li><a href="#" class="text-base text-gray-300 hover:text-white">{{ __('Press') }}</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">{{ __('Legal') }}</h3>
                        <ul class="mt-4 space-y-4">
                            <li><a href="#" class="text-base text-gray-300 hover:text-white">{{ __('Privacy') }}</a></li>
                            <li><a href="#" class="text-base text-gray-300 hover:text-white">{{ __('Terms') }}</a></li>
                            <li><a href="#" class="text-base text-gray-300 hover:text-white">{{ __('Cookie Policy') }}</a></li>
                            <li><a href="#" class="text-base text-gray-300 hover:text-white">{{ __('Licenses') }}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-12 border-t border-gray-700 pt-8">
                    <p class="text-base text-gray-400 xl:text-center">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}
                    </p>
                </div>
            </div>
        </footer>
    </body>
</html>
