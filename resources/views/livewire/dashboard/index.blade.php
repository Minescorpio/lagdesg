<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Total Sales Today -->
                <div class="stats-card">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="stats-icon text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-400 truncate">
                                    {{ __('Total Sales Today') }}
                                </dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-white">
                                        {{ number_format($totalSalesToday ?? 0, 2) }} €
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="stats-card">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="stats-icon text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-400 truncate">
                                    {{ __('Total Orders') }}
                                </dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-white">
                                        {{ $totalOrders ?? 0 }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Low Stock Items -->
                <div class="stats-card">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="stats-icon text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-400 truncate">
                                    {{ __('Low Stock Items') }}
                                </dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-white">
                                        {{ $lowStockItems ?? 0 }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Total Customers -->
                <div class="stats-card">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="stats-icon text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-400 truncate">
                                    {{ __('Total Customers') }}
                                </dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-white">
                                        {{ $totalCustomers ?? 0 }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8">
                <h2 class="text-xl font-semibold text-white mb-4">{{ __('Quick Actions') }}</h2>
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- New Sale -->
                    <a href="{{ route('pos.terminal') }}" class="transform hover:scale-105 transition-all duration-200">
                        <div class="quick-action">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="quick-action-icon text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="ml-5">
                                    <h3 class="text-lg font-medium text-white">{{ __('New Sale') }}</h3>
                                    <p class="text-sm text-gray-400">{{ __('Create a new sale transaction') }}</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Add Product -->
                    <a href="{{ route('products.create') }}" class="transform hover:scale-105 transition-all duration-200">
                        <div class="quick-action">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="quick-action-icon text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <div class="ml-5">
                                    <h3 class="text-lg font-medium text-white">{{ __('Add Product') }}</h3>
                                    <p class="text-sm text-gray-400">{{ __('Add a new product to inventory') }}</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Add Customer -->
                    <a href="{{ route('customers.create') }}" class="transform hover:scale-105 transition-all duration-200">
                        <div class="quick-action">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="quick-action-icon text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                </div>
                                <div class="ml-5">
                                    <h3 class="text-lg font-medium text-white">{{ __('Add Customer') }}</h3>
                                    <p class="text-sm text-gray-400">{{ __('Register a new customer') }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Sales -->
            <div class="mt-8">
                <h2 class="text-xl font-semibold text-white mb-4">{{ __('Recent Sales') }}</h2>
                <div class="recent-sales">
                    <div class="recent-sales-body">
                        <div class="overflow-x-auto">
                            <table>
                                <thead>
                                    <tr>
                                        <th>{{ __('Receipt No') }}</th>
                                        <th>{{ __('Customer') }}</th>
                                        <th class="text-right">{{ __('Total') }}</th>
                                        <th class="text-center">{{ __('Payment Method') }}</th>
                                        <th>{{ __('Date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentSales ?? [] as $sale)
                                        <tr>
                                            <td class="py-4">
                                                <div class="font-medium text-white">{{ $sale->receipt_no }}</div>
                                            </td>
                                            <td class="text-gray-400">{{ $sale->customer->name ?? 'Guest' }}</td>
                                            <td class="text-right text-white">{{ number_format($sale->total_amount, 2) }} €</td>
                                            <td class="text-center">
                                                <span class="badge badge-success">{{ $sale->payment_method }}</span>
                                            </td>
                                            <td class="text-gray-400">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-12">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                                    </svg>
                                                    <h3 class="mt-2 text-sm font-medium text-gray-400">{{ __('No recent sales') }}</h3>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 