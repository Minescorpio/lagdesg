<div>
    <div class="min-h-screen bg-gray-100 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="sm:flex sm:items-center sm:justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-white sm:text-3xl sm:truncate">
                        {{ __('Sales History') }}
                    </h2>
                    <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:space-x-6">
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <i class="fas fa-calendar-alt flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"></i>
                            {{ now()->format('d M Y') }}
                        </div>
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <i class="fas fa-chart-bar flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"></i>
                            {{ __('Total Sales') }}: {{ $totalSales }}
                        </div>
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <i class="fas fa-dollar-sign flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"></i>
                            {{ __('Total Revenue') }}: {{ money($totalRevenue) }}
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex sm:mt-0 sm:ml-4">
                    <a href="{{ route('pos.terminal') }}" class="btn-primary">
                        <i class="fas fa-plus mr-2"></i>{{ __('New Sale') }}
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <div class="mt-6 bg-white shadow-sm rounded-lg p-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Search -->
                    <div class="col-span-1 sm:col-span-2">
                        <label for="search" class="sr-only">{{ __('Search') }}</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="search" wire:model.live.debounce.300ms="search" 
                                class="form-input pl-10" 
                                placeholder="{{ __('Search by receipt number, customer name or cashier...') }}">
                        </div>
                    </div>

                    <!-- Date Range -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">{{ __('Start Date') }}</label>
                        <input type="date" wire:model.live="startDate" id="start_date" class="form-input">
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">{{ __('End Date') }}</label>
                        <input type="date" wire:model.live="endDate" id="end_date" class="form-input">
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Payment Method -->
                    <div>
                        <select wire:model.live="paymentMethod" class="form-select">
                            <option value="">{{ __('All Payment Methods') }}</option>
                            <option value="cash">{{ __('Cash') }}</option>
                            <option value="card">{{ __('Card') }}</option>
                        </select>
                    </div>

                    <!-- Cashier -->
                    <div>
                        <select wire:model.live="cashierId" class="form-select">
                            <option value="">{{ __('All Cashiers') }}</option>
                            @foreach($cashiers as $cashier)
                                <option value="{{ $cashier->id }}">{{ $cashier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Amount Range -->
                    <div>
                        <input type="number" wire:model.live="minAmount" 
                            class="form-input" 
                            placeholder="{{ __('Min Amount') }}"
                            step="0.01">
                    </div>

                    <div>
                        <input type="number" wire:model.live="maxAmount" 
                            class="form-input" 
                            placeholder="{{ __('Max Amount') }}"
                            step="0.01">
                    </div>
                </div>

                <!-- Quick Filters -->
                <div class="mt-4 flex flex-wrap gap-2">
                    <button wire:click="setQuickFilter('today')" 
                        class="filter-btn {{ $quickFilter === 'today' ? 'active' : '' }}">
                        {{ __('Today') }}
                    </button>
                    <button wire:click="setQuickFilter('yesterday')" 
                        class="filter-btn {{ $quickFilter === 'yesterday' ? 'active' : '' }}">
                        {{ __('Yesterday') }}
                    </button>
                    <button wire:click="setQuickFilter('this_week')" 
                        class="filter-btn {{ $quickFilter === 'this_week' ? 'active' : '' }}">
                        {{ __('This Week') }}
                    </button>
                    <button wire:click="setQuickFilter('this_month')" 
                        class="filter-btn {{ $quickFilter === 'this_month' ? 'active' : '' }}">
                        {{ __('This Month') }}
                    </button>
                    <button wire:click="setQuickFilter('last_month')" 
                        class="filter-btn {{ $quickFilter === 'last_month' ? 'active' : '' }}">
                        {{ __('Last Month') }}
                    </button>
                </div>
            </div>

            <!-- Sales Table -->
            <div class="mt-6 bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="table-th">
                                    <button wire:click="sortBy('receipt_number')" class="group flex items-center space-x-1">
                                        <span>{{ __('Receipt No.') }}</span>
                                        @if($sortField === 'receipt_number')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-blue-500"></i>
                                        @else
                                            <i class="fas fa-sort text-gray-400 group-hover:text-gray-500"></i>
                                        @endif
                                    </button>
                                </th>
                                <th scope="col" class="table-th">{{ __('Customer') }}</th>
                                <th scope="col" class="table-th">{{ __('Items') }}</th>
                                <th scope="col" class="table-th">
                                    <button wire:click="sortBy('total_amount')" class="group flex items-center space-x-1">
                                        <span>{{ __('Total') }}</span>
                                        @if($sortField === 'total_amount')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-blue-500"></i>
                                        @else
                                            <i class="fas fa-sort text-gray-400 group-hover:text-gray-500"></i>
                                        @endif
                                    </button>
                                </th>
                                <th scope="col" class="table-th">{{ __('Payment') }}</th>
                                <th scope="col" class="table-th">{{ __('Cashier') }}</th>
                                <th scope="col" class="table-th">
                                    <button wire:click="sortBy('created_at')" class="group flex items-center space-x-1">
                                        <span>{{ __('Date') }}</span>
                                        @if($sortField === 'created_at')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-blue-500"></i>
                                        @else
                                            <i class="fas fa-sort text-gray-400 group-hover:text-gray-500"></i>
                                        @endif
                                    </button>
                                </th>
                                <th scope="col" class="table-th">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($sales as $sale)
                                <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                                    <td class="table-td font-medium">{{ $sale->receipt_number }}</td>
                                    <td class="table-td">
                                        @if($sale->customer)
                                            <div class="flex items-center">
                                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <i class="fas fa-user text-blue-600"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="font-medium text-white">{{ $sale->customer->name }}</div>
                                                    @if($sale->loyalty_points_earned)
                                                        <div class="text-sm text-blue-600">+{{ $sale->loyalty_points_earned }} {{ __('points') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-500">{{ __('Walk-in Customer') }}</span>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        <div class="flex items-center">
                                            <span class="font-medium">{{ $sale->items_count }}</span>
                                            <button wire:click="showItems({{ $sale->id }})" class="ml-2 text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="table-td font-medium">{{ money($sale->total_amount) }}</td>
                                    <td class="table-td">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $sale->payment_method === 'cash' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst($sale->payment_method) }}
                                        </span>
                                    </td>
                                    <td class="table-td">{{ $sale->cashier->name }}</td>
                                    <td class="table-td">
                                        <div class="text-sm">
                                            <div>{{ $sale->created_at->format('d M Y') }}</div>
                                            <div class="text-gray-500">{{ $sale->created_at->format('H:i') }}</div>
                                        </div>
                                    </td>
                                    <td class="table-td">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('sales.show', $sale) }}" class="action-button">
                                                <i class="fas fa-receipt"></i>
                                            </a>
                                            <button wire:click="emailReceipt({{ $sale->id }})" class="action-button">
                                                <i class="fas fa-envelope"></i>
                                            </button>
                                            @if($sale->created_at->isToday())
                                                <button wire:click="voidSale({{ $sale->id }})" 
                                                    wire:confirm="{{ __('Are you sure you want to void this sale?') }}"
                                                    class="action-button text-red-600 hover:text-red-800">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-10 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-receipt text-4xl mb-4"></i>
                                            <span class="text-xl font-medium">{{ __('No sales found') }}</span>
                                            <span class="text-sm mt-2">{{ __('Try adjusting your search or filters') }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($sales->hasPages())
                    <div class="border-t border-gray-200 px-4 py-3">
                        {{ $sales->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sale Items Modal -->
    <div x-data="{ show: false }"
        x-show="show"
        x-on:show-items-modal.window="show = true"
        x-on:close-modal.window="show = false"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="relative bg-white rounded-lg max-w-2xl w-full">
                <div class="p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-white">{{ __('Sale Items') }}</h3>
                        <button x-on:click="show = false" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    @if($selectedSale)
                        <div class="space-y-4">
                            @foreach($selectedSale->items as $item)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            @if($item->product->image)
                                                <img src="{{ Storage::url($item->product->image) }}" 
                                                    alt="{{ $item->product->name }}"
                                                    class="h-12 w-12 rounded-lg object-cover">
                                            @else
                                                <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                                    <i class="fas fa-box text-gray-400"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-medium text-white">{{ $item->product->name }}</p>
                                            <p class="text-sm text-gray-500">SKU: {{ $item->product->sku }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium text-white">{{ money($item->total_price) }}</p>
                                        <p class="text-sm text-gray-500">
                                            {{ money($item->unit_price) }} × {{ $item->quantity }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach

                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">{{ __('Subtotal') }}</span>
                                    <span class="font-medium">{{ money($selectedSale->subtotal) }}</span>
                                </div>
                                <div class="flex justify-between text-sm mt-2">
                                    <span class="text-gray-500">{{ __('Tax') }} ({{ $selectedSale->tax_rate }}%)</span>
                                    <span class="font-medium">{{ money($selectedSale->tax_amount) }}</span>
                                </div>
                                @if($selectedSale->discount_amount > 0)
                                    <div class="flex justify-between text-sm mt-2">
                                        <span class="text-gray-500">
                                            {{ __('Discount') }} 
                                            @if($selectedSale->discount_type === 'percentage')
                                                ({{ $selectedSale->discount_percentage }}%)
                                            @endif
                                        </span>
                                        <span class="font-medium text-red-600">
                                            -{{ money($selectedSale->discount_amount) }}
                                        </span>
                                    </div>
                                @endif
                                <div class="flex justify-between text-lg font-bold mt-4 pt-4 border-t border-gray-200">
                                    <span>{{ __('Total') }}</span>
                                    <span>{{ money($selectedSale->total_amount) }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .table-th {
        @apply px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider;
    }
    .table-td {
        @apply px-6 py-4 whitespace-nowrap text-sm;
    }
    .action-button {
        @apply p-2 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
    }
    .filter-btn {
        @apply px-3 py-1 rounded-full text-sm font-medium transition-colors duration-200;
    }
    .filter-btn:not(.active) {
        @apply text-gray-700 bg-gray-100 hover:bg-gray-200;
    }
    .filter-btn.active {
        @apply text-blue-700 bg-blue-100;
    }
    .form-input {
        @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
    }
    .form-select {
        @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
    }
    .btn-primary {
        @apply inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
    }
</style>
@endpush 