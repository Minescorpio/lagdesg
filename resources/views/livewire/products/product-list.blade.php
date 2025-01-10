<div>
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-white">{{ __('Products') }}</h2>
        <a href="{{ route('products.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>{{ __('Add Product') }}
        </a>
    </div>

    <!-- Search -->
    <div class="mb-4">
        <div class="relative rounded-md shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="search" wire:model.live.debounce.300ms="search"
                class="form-input block w-full pl-10 text-white bg-[#374151] border-gray-600 focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="{{ __('Search products...') }}">
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="py-4 text-left text-white">{{ __('Nom') }}</th>
                    <th class="py-4 text-left text-white">{{ __('Catégorie') }}</th>
                    <th class="py-4 text-right text-white">{{ __('Prix') }}</th>
                    <th class="py-4 text-right text-white">{{ __('Stock') }}</th>
                    <th class="py-4 text-center text-white">{{ __('Statut') }}</th>
                    <th class="py-4 text-right text-white">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($products as $product)
                    <tr class="hover:bg-[#2E324A] transition-colors duration-200">
                        <td class="px-6 py-4">
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
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                style="background-color: {{ $product->category->color ?? '#E5E7EB' }}20; color: {{ $product->category->color ?? '#374151' }}">
                                {{ $product->category->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm text-white">{{ money($product->price) }}</div>
                            <div class="text-xs text-gray-500">{{ __('Cost') }}: {{ money($product->cost_price) }}</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($product->track_stock)
                                <div class="flex items-center justify-end">
                                    <span class="mr-2 h-2.5 w-2.5 rounded-full 
                                        {{ $product->current_stock <= $product->min_stock_alert ? 'bg-red-500' : 'bg-green-500' }}">
                                    </span>
                                    <span class="text-white">{{ $product->current_stock }}</span>
                                </div>
                            @else
                                <span class="text-gray-500">{{ __('Not tracked') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="toggleActive({{ $product->id }})" 
                                class="status-badge {{ $product->active ? 'status-badge-success' : 'status-badge-danger' }}">
                                {{ $product->active ? __('Active') : __('Inactive') }}
                            </button>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-3">
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
                        <td colspan="6" class="px-6 py-10 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-box-open text-4xl mb-4 text-white"></i>
                                <span class="text-xl font-medium text-white">{{ __('No products found') }}</span>
                                <span class="text-sm mt-2 text-white">{{ __('Try adjusting your search') }}</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @endif
</div>

@push('styles')
<style>
    /* Table Styles */
    table {
        @apply w-full;
    }
    
    tbody {
        @apply divide-y divide-gray-700;
    }
    
    tr {
        @apply hover:bg-[#2E324A] transition-colors duration-200;
    }
    
    td {
        @apply px-6 py-4 whitespace-nowrap text-sm text-white;
    }

    .status-badge {
        @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
    }

    .status-badge-success {
        @apply bg-green-100 text-green-800;
    }

    .status-badge-danger {
        @apply bg-red-100 text-red-800;
    }

    .action-button {
        @apply p-2 text-gray-400 hover:text-white focus:outline-none;
    }
</style>
@endpush 