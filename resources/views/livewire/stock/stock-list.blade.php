<div>
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-white sm:text-3xl sm:truncate">
                {{ __('Stock Management') }}
            </h2>
        </div>
        <div class="mt-4 flex sm:mt-0 sm:ml-4">
            <a href="{{ route('stock.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>{{ __('Add Stock') }}
            </a>
        </div>
    </div>

    <div class="mt-6 bg-white shadow-sm rounded-lg">
        <!-- Filters -->
        <div class="p-4 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
            <div class="flex-1 min-w-0 space-y-3 sm:space-y-0 sm:flex sm:items-center sm:space-x-4">
                <!-- Search -->
                <div class="flex-1">
                    <label for="search" class="sr-only">{{ __('Search') }}</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="search" wire:model.live.debounce.300ms="search" 
                            class="form-input pl-10" 
                            placeholder="{{ __('Search products...') }}">
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="sm:w-48">
                    <select wire:model.live="selectedCategory" class="form-select">
                        <option value="">{{ __('All Categories') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Stock Level Filter -->
                <div class="sm:w-48">
                    <select wire:model.live="stockLevel" class="form-select">
                        <option value="">{{ __('All Stock Levels') }}</option>
                        <option value="low">{{ __('Low Stock') }}</option>
                        <option value="out">{{ __('Out of Stock') }}</option>
                        <option value="available">{{ __('In Stock') }}</option>
                    </select>
                </div>
            </div>

            <!-- Per Page -->
            <div class="mt-3 sm:mt-0 sm:ml-4">
                <select wire:model.live="perPage" class="form-select">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="table-th">
                            <button wire:click="sortBy('product_name')" class="group flex items-center space-x-1">
                                <span>{{ __('Product') }}</span>
                                @if($sortField === 'product_name')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-blue-500"></i>
                                @else
                                    <i class="fas fa-sort text-gray-400 group-hover:text-gray-500"></i>
                                @endif
                            </button>
                        </th>
                        <th scope="col" class="table-th">{{ __('Category') }}</th>
                        <th scope="col" class="table-th">
                            <button wire:click="sortBy('quantity')" class="group flex items-center space-x-1">
                                <span>{{ __('Quantity') }}</span>
                                @if($sortField === 'quantity')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-blue-500"></i>
                                @else
                                    <i class="fas fa-sort text-gray-400 group-hover:text-gray-500"></i>
                                @endif
                            </button>
                        </th>
                        <th scope="col" class="table-th">{{ __('Alert Level') }}</th>
                        <th scope="col" class="table-th">{{ __('Status') }}</th>
                        <th scope="col" class="table-th">{{ __('Last Updated') }}</th>
                        <th scope="col" class="table-th">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($stocks as $stock)
                        <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                            <td class="table-td">
                                <div class="flex items-center">
                                    @if($stock->product->image)
                                        <img src="{{ Storage::url($stock->product->image) }}" alt="{{ $stock->product->name }}" 
                                            class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-box text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="font-medium text-white">{{ $stock->product->name }}</div>
                                        <div class="text-sm text-gray-500">SKU: {{ $stock->product->sku }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="table-td">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $stock->product->category->name }}
                                </span>
                            </td>
                            <td class="table-td">
                                <div class="text-sm font-medium text-white">{{ $stock->quantity }}</div>
                            </td>
                            <td class="table-td">
                                <div class="text-sm text-gray-500">{{ $stock->alert_level }}</div>
                            </td>
                            <td class="table-td">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $stock->quantity <= 0 ? 'bg-red-100 text-red-800' : 
                                       ($stock->quantity <= $stock->alert_level ? 'bg-yellow-100 text-yellow-800' : 
                                        'bg-green-100 text-green-800') }}">
                                    {{ $stock->quantity <= 0 ? __('Out of Stock') : 
                                       ($stock->quantity <= $stock->alert_level ? __('Low Stock') : __('In Stock')) }}
                                </span>
                            </td>
                            <td class="table-td">
                                <div class="text-sm text-gray-500">
                                    {{ $stock->updated_at->diffForHumans() }}
                                </div>
                            </td>
                            <td class="table-td">
                                <div class="flex items-center space-x-3">
                                    <button wire:click="adjustStock({{ $stock->id }})" 
                                        class="action-button text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-plus-minus"></i>
                                    </button>
                                    <a href="{{ route('stock.edit', $stock) }}" 
                                        class="action-button">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button wire:click="delete({{ $stock->id }})"
                                        wire:confirm="{{ __('Are you sure you want to delete this stock record?') }}"
                                        class="action-button text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-box-open text-4xl mb-4"></i>
                                    <span class="text-xl font-medium">{{ __('No stock records found') }}</span>
                                    <span class="text-sm mt-2">{{ __('Try adjusting your search or filters') }}</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($stocks->hasPages())
            <div class="border-t border-gray-200 px-4 py-3">
                {{ $stocks->links() }}
            </div>
        @endif
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
    .btn-primary {
        @apply inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
    }
    .form-select {
        @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
    }
    .form-input {
        @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
    }
</style>
@endpush 