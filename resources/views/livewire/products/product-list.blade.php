<div>
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-white sm:text-3xl sm:truncate">
                {{ __('Products') }}
            </h2>
        </div>
        <div class="mt-4 flex sm:mt-0 sm:ml-4">
            <a href="{{ route('products.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>{{ __('Add Product') }}
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

                <!-- Show Inactive Toggle -->
                <div class="flex items-center">
                    <button type="button" wire:click="$toggle('showInactive')" 
                        class="flex items-center {{ $showInactive ? 'text-blue-600' : 'text-gray-500' }}">
                        <span class="mr-2">
                            <i class="fas {{ $showInactive ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                        </span>
                        {{ __('Show Inactive') }}
                    </button>
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
                            <button wire:click="sortBy('name')" class="group flex items-center space-x-1">
                                <span>{{ __('Name') }}</span>
                                @if($sortField === 'name')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-blue-500"></i>
                                @else
                                    <i class="fas fa-sort text-gray-400 group-hover:text-gray-500"></i>
                                @endif
                            </button>
                        </th>
                        <th scope="col" class="table-th">{{ __('Category') }}</th>
                        <th scope="col" class="table-th">{{ __('Price') }}</th>
                        <th scope="col" class="table-th">{{ __('Stock') }}</th>
                        <th scope="col" class="table-th">{{ __('Status') }}</th>
                        <th scope="col" class="table-th">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products as $product)
                        <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                            <td class="table-td">
                                <div class="flex items-center">
                                    @if($product->image_path)
                                        <img src="{{ Storage::url($product->image_path) }}" 
                                            alt="{{ $product->name }}"
                                            class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-box text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="font-medium text-white">{{ $product->name }}</div>
                                        @if($product->barcode)
                                            <div class="text-sm text-gray-500">{{ $product->barcode }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="table-td">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    style="background-color: {{ $product->category->color ?? '#E5E7EB' }}20; color: {{ $product->category->color ?? '#374151' }}">
                                    {{ $product->category->name }}
                                </span>
                            </td>
                            <td class="table-td">
                                <div class="text-sm text-white">{{ money($product->price) }}</div>
                                <div class="text-xs text-gray-500">{{ __('Cost') }}: {{ money($product->cost_price) }}</div>
                            </td>
                            <td class="table-td">
                                @if($product->track_stock)
                                    <div class="flex items-center">
                                        <span class="mr-2 h-2.5 w-2.5 rounded-full 
                                            {{ $product->current_stock <= $product->min_stock_alert ? 'bg-red-500' : 'bg-green-500' }}">
                                        </span>
                                        <span>{{ $product->current_stock }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-500">{{ __('Not tracked') }}</span>
                                @endif
                            </td>
                            <td class="table-td">
                                <button wire:click="toggleActive({{ $product->id }})" 
                                    class="status-badge {{ $product->active ? 'status-badge-success' : 'status-badge-danger' }}">
                                    {{ $product->active ? __('Active') : __('Inactive') }}
                                </button>
                            </td>
                            <td class="table-td">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('products.edit', $product) }}" 
                                        class="action-button">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button wire:click="delete({{ $product->id }})"
                                        wire:confirm="{{ __('Are you sure you want to delete this product?') }}"
                                        class="action-button text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-box-open text-4xl mb-4"></i>
                                    <span class="text-xl font-medium">{{ __('No products found') }}</span>
                                    <span class="text-sm mt-2">{{ __('Try adjusting your search or filters') }}</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="border-t border-gray-200 px-4 py-3">
                {{ $products->links() }}
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
    .status-badge {
        @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium focus:outline-none;
    }
    .status-badge-success {
        @apply bg-green-100 text-green-800;
    }
    .status-badge-danger {
        @apply bg-red-100 text-red-800;
    }
</style>
@endpush 