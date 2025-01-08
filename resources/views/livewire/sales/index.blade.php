<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Sales') }}
            </h2>
            <a href="{{ route('pos.terminal') }}" class="btn-primary">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('New Sale') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-body">
                    <!-- Search and Filters -->
                    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <!-- Search -->
                        <div class="relative">
                            <input type="search" wire:model.live="search" placeholder="{{ __('Search sales...') }}" class="search-input" />
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Date Range -->
                        <div class="relative">
                            <select wire:model.live="dateRange" class="form-input">
                                <option value="today">{{ __('Today') }}</option>
                                <option value="yesterday">{{ __('Yesterday') }}</option>
                                <option value="last7days">{{ __('Last 7 Days') }}</option>
                                <option value="last30days">{{ __('Last 30 Days') }}</option>
                                <option value="thisMonth">{{ __('This Month') }}</option>
                                <option value="lastMonth">{{ __('Last Month') }}</option>
                                <option value="all">{{ __('All Time') }}</option>
                            </select>
                        </div>

                        <!-- Payment Method -->
                        <div class="relative">
                            <select wire:model.live="paymentMethod" class="form-input">
                                <option value="">{{ __('All Payment Methods') }}</option>
                                <option value="cash">{{ __('Cash') }}</option>
                                <option value="card">{{ __('Card') }}</option>
                                <option value="bank_transfer">{{ __('Bank Transfer') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Sales Stats -->
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                        <!-- Total Sales -->
                        <div class="stats-card">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="stats-icon text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-400 truncate">{{ __('Total Sales') }}</dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-white">{{ number_format($totalSales, 2) }} €</div>
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
                                        <dt class="text-sm font-medium text-gray-400 truncate">{{ __('Total Orders') }}</dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-white">{{ $totalOrders }}</div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <!-- Average Order Value -->
                        <div class="stats-card">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="stats-icon text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-400 truncate">{{ __('Average Order') }}</dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-white">{{ number_format($averageOrderValue, 2) }} €</div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <!-- Total Tax -->
                        <div class="stats-card">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="stats-icon text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-400 truncate">{{ __('Total Tax') }}</dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-white">{{ number_format($totalTax, 2) }} €</div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Table -->
                    <div class="overflow-x-auto">
                        <table>
                            <thead>
                                <tr>
                                    <th>{{ __('Receipt No') }}</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th class="text-right">{{ __('Items') }}</th>
                                    <th class="text-right">{{ __('Total') }}</th>
                                    <th class="text-center">{{ __('Payment Method') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th class="text-right">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @forelse($sales as $sale)
                                    <tr class="hover:bg-[#2E324A] transition-colors duration-200">
                                        <td class="py-4">
                                            <div class="font-medium text-white">{{ $sale->receipt_no }}</div>
                                        </td>
                                        <td class="text-gray-400">{{ $sale->customer->full_name ?? 'Guest' }}</td>
                                        <td class="text-right text-white">{{ $sale->items_count }}</td>
                                        <td class="text-right text-white">{{ number_format($sale->total_amount, 2) }} €</td>
                                        <td class="text-center">
                                            <span class="badge badge-success">{{ $sale->payment_method }}</span>
                                        </td>
                                        <td class="text-gray-400">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-right">
                                            <div class="flex items-center justify-end space-x-3">
                                                <button wire:click="showReceipt({{ $sale->id }})" class="btn-secondary btn-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </button>
                                                <button wire:click="confirmDelete({{ $sale->id }})" class="btn-danger btn-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-12">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                                </svg>
                                                <h3 class="mt-2 text-sm font-medium text-gray-400">{{ __('No sales found') }}</h3>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $sales->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ show: @entangle('showDeleteModal') }"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-lg bg-[#1F2937] px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-600 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                        <h3 class="text-lg font-semibold text-white">{{ __('Delete Sale') }}</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-400">{{ __('Are you sure you want to delete this sale? This action cannot be undone.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button"
                            wire:click="deleteSale"
                            class="btn-danger w-full sm:w-auto sm:ml-3">
                        {{ __('Delete') }}
                    </button>
                    <button type="button"
                            wire:click="$set('showDeleteModal', false)"
                            class="btn-secondary w-full sm:w-auto mt-3 sm:mt-0">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Receipt Modal -->
    <div x-data="{ show: @entangle('showReceiptModal') }"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-lg bg-[#1F2937] px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                @if($selectedSale)
                    <div class="sm:flex sm:items-start">
                        <div class="w-full">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-white">{{ __('Receipt') }} #{{ $selectedSale->receipt_no }}</h3>
                                <button wire:click="$set('showReceiptModal', false)" class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div class="space-y-4">
                                <!-- Customer Info -->
                                <div class="text-sm text-gray-400">
                                    <p>{{ $selectedSale->customer->full_name ?? 'Guest' }}</p>
                                    @if($selectedSale->customer)
                                        <p>{{ $selectedSale->customer->email }}</p>
                                        <p>{{ $selectedSale->customer->phone }}</p>
                                    @endif
                                </div>

                                <!-- Items -->
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-left text-gray-400">{{ __('Item') }}</th>
                                            <th class="text-right text-gray-400">{{ __('Qty') }}</th>
                                            <th class="text-right text-gray-400">{{ __('Price') }}</th>
                                            <th class="text-right text-gray-400">{{ __('Total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-700">
                                        @foreach($selectedSale->items as $item)
                                            <tr>
                                                <td class="py-2 text-white">{{ $item->product->name }}</td>
                                                <td class="py-2 text-right text-white">{{ $item->quantity }}</td>
                                                <td class="py-2 text-right text-white">{{ number_format($item->unit_price, 2) }} €</td>
                                                <td class="py-2 text-right text-white">{{ number_format($item->total, 2) }} €</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="border-t border-gray-700">
                                        <tr>
                                            <td colspan="3" class="py-2 text-right text-gray-400">{{ __('Subtotal') }}</td>
                                            <td class="py-2 text-right text-white">{{ number_format($selectedSale->subtotal, 2) }} €</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="py-2 text-right text-gray-400">{{ __('Tax') }}</td>
                                            <td class="py-2 text-right text-white">{{ number_format($selectedSale->tax, 2) }} €</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="py-2 text-right font-semibold text-white">{{ __('Total') }}</td>
                                            <td class="py-2 text-right font-semibold text-white">{{ number_format($selectedSale->total_amount, 2) }} €</td>
                                        </tr>
                                    </tfoot>
                                </table>

                                <!-- Payment Info -->
                                <div class="text-sm">
                                    <div class="flex justify-between py-2">
                                        <span class="text-gray-400">{{ __('Payment Method') }}</span>
                                        <span class="text-white">{{ $selectedSale->payment_method }}</span>
                                    </div>
                                    @if($selectedSale->payment_method === 'cash')
                                        <div class="flex justify-between py-2">
                                            <span class="text-gray-400">{{ __('Received') }}</span>
                                            <span class="text-white">{{ number_format($selectedSale->received_amount, 2) }} €</span>
                                        </div>
                                        <div class="flex justify-between py-2">
                                            <span class="text-gray-400">{{ __('Change') }}</span>
                                            <span class="text-white">{{ number_format($selectedSale->change_amount, 2) }} €</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Date & Notes -->
                                <div class="text-sm text-gray-400">
                                    <p>{{ $selectedSale->created_at->format('d/m/Y H:i') }}</p>
                                    @if($selectedSale->notes)
                                        <p class="mt-2">{{ $selectedSale->notes }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button wire:click="printReceipt({{ $selectedSale->id }})" class="btn-primary">
                                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    {{ __('Print Receipt') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
