<nav x-data="{ open: false }" class="bg-[#2E324A] border-b border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/logo.png') }}" class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                @auth
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('pos.terminal')" :active="request()->routeIs('pos.terminal')">
                        {{ __('POS Terminal') }}
                    </x-nav-link>

                    <x-nav-link :href="route('pos.customers.index')" :active="request()->routeIs('pos.customers.*')">
                        {{ __('Customers') }}
                    </x-nav-link>

                    <x-nav-link :href="route('pos.categories.index')" :active="request()->routeIs('pos.categories.*')">
                        {{ __('Categories') }}
                    </x-nav-link>

                    <x-nav-link :href="route('pos.products.index')" :active="request()->routeIs('pos.products.*')">
                        {{ __('Products') }}
                    </x-nav-link>

                    <x-nav-link :href="route('pos.sales.index')" :active="request()->routeIs('pos.sales.*')">
                        {{ __('Sales') }}
                    </x-nav-link>
                </div>
                @endauth
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <!-- Settings Dropdown -->
                @auth
                <div class="ml-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-gray-300 hover:text-gray-100 hover:border-gray-300 focus:outline-none focus:text-gray-100 focus:border-gray-300 transition duration-150 ease-in-out">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
                @else
                <div class="ml-3 relative">
                    <a href="{{ route('login') }}" class="text-sm text-gray-300 hover:text-gray-100">{{ __('Login') }}</a>
                </div>
                @endauth
            </div>

            <!-- Hamburger -->
            @auth
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            @endauth
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    @auth
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('pos.terminal')" :active="request()->routeIs('pos.terminal')">
                {{ __('POS Terminal') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('pos.customers.index')" :active="request()->routeIs('pos.customers.*')">
                {{ __('Customers') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('pos.categories.index')" :active="request()->routeIs('pos.categories.*')">
                {{ __('Categories') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('pos.products.index')" :active="request()->routeIs('pos.products.*')">
                {{ __('Products') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('pos.sales.index')" :active="request()->routeIs('pos.sales.*')">
                {{ __('Sales') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-300">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
    @endauth
</nav> 