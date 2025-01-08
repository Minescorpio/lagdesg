<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Sales Today -->
                <div class="stats-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="stats-label">{{ __('Total des ventes aujourd\'hui') }}</p>
                            <p class="stats-value">{{ number_format($totalSalesToday, 2, ',', ' ') }} €</p>
                        </div>
                        <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="stats-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="stats-label">{{ __('Total des commandes') }}</p>
                            <p class="stats-value">{{ $totalOrders }}</p>
                        </div>
                        <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                </div>

                <!-- Low Stock Items -->
                <div class="stats-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="stats-label">{{ __('Articles en stock bas') }}</p>
                            <p class="stats-value">{{ $lowStockItems }}</p>
                        </div>
                        <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                </div>

                <!-- Total Customers -->
                <div class="stats-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="stats-label">{{ __('Total des clients') }}</p>
                            <p class="stats-value">{{ $totalCustomers }}</p>
                        </div>
                        <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-white mb-4">{{ __('Actions rapides') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- New Sale -->
                    <a href="{{ route('pos.terminal') }}" class="btn-primary flex items-center justify-center gap-2 py-2">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('Nouvelle vente') }}
                    </a>

                    <!-- Add Product -->
                    <a href="{{ route('products.create') }}" class="btn-primary flex items-center justify-center gap-2 py-2">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('Ajouter un produit') }}
                    </a>

                    <!-- Add Customer -->
                    <a href="{{ route('customers.create') }}" class="btn-primary flex items-center justify-center gap-2 py-2">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('Ajouter un client') }}
                    </a>
                </div>
            </div>

            <!-- Recent Sales -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-white">{{ __('Ventes récentes') }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="table-header px-6 py-3">{{ __('N° DE REÇU') }}</th>
                                <th class="table-header px-6 py-3">{{ __('CLIENT') }}</th>
                                <th class="table-header px-6 py-3">{{ __('TOTAL') }}</th>
                                <th class="table-header px-6 py-3">{{ __('MODE DE PAIEMENT') }}</th>
                                <th class="table-header px-6 py-3">{{ __('DATE') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @forelse($recentSales as $sale)
                                <tr class="hover:bg-[#2E324A] transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $sale->receipt_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $sale->customer ? $sale->customer->name : '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($sale->total_amount, 2, ',', ' ') }} €</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $sale->payment_method }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-400">
                                        {{ __('Aucune vente récente trouvée') }}
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