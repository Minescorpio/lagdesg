<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Products') }}
            </h2>
            <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 rounded-lg text-white hover:bg-indigo-700 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('New Product') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-body">
                    <!-- Search -->
                    <div class="mb-4">
                        <div class="relative">
                            <input type="search" wire:model.live="search" placeholder="{{ __('Search products...') }}" class="search-input" />
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Products Table -->
                    <div class="overflow-x-auto">
                        <table>
                            <thead>
                                <tr>
                                    <th class="text-left text-white">{{ __('Name') }}</th>
                                    <th class="text-left text-white">{{ __('Category') }}</th>
                                    <th class="text-right text-white">{{ __('Price') }}</th>
                                    <th class="text-right text-white">{{ __('Stock') }}</th>
                                    <th class="text-center text-white">{{ __('Status') }}</th>
                                    <th class="text-right text-white">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>
                                            <div class="font-medium text-white">{{ $product->name }}</div>
                                            <div class="text-sm text-gray-400">{{ $product->barcode }}</div>
                                        </td>
                                        <td class="text-gray-400">
                                            {{ $product->category?->name ?? __('Uncategorized') }}
                                        </td>
                                        <td class="text-right text-white">
                                            {{ number_format($product->price, 2) }} €
                                        </td>
                                        <td class="text-right">
                                            <span class="@if($product->current_stock <= $product->min_stock_alert) text-red-400 @else text-gray-400 @endif">
                                                {{ $product->current_stock }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($product->active)
                                                <span class="badge badge-success">{{ __('Active') }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ __('Inactive') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('products.edit', $product) }}" class="btn-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <button wire:click="confirmDelete({{ $product->id }})" class="btn-sm text-red-500 hover:text-red-700">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-12">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                </svg>
                                                <h3 class="mt-2 text-sm font-medium text-gray-400">{{ __('No products found') }}</h3>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $products->links() }}
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
                        <h3 class="text-lg font-semibold text-white">{{ __('Delete Product') }}</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-400">{{ __('Are you sure you want to delete this product? This action cannot be undone.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button"
                            wire:click="deleteProduct"
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
</div>

@push('styles')
<style>
    /* Table Styles */
    .table-container {
        @apply overflow-x-auto bg-[#1F2937] rounded-lg shadow-sm;
    }
    
    table {
        @apply min-w-full divide-y divide-gray-700;
    }
    
    thead {
        @apply bg-[#2E324A];
    }
    
    thead th {
        @apply px-6 py-3 text-left text-white font-medium;
    }
    
    tbody {
        @apply bg-[#1F2937] divide-y divide-gray-700;
    }
    
    tbody tr {
        @apply hover:bg-[#374151] transition-colors duration-150;
    }
    
    tbody tr:nth-child(even) {
        @apply bg-[#2E324A];
    }
    
    td {
        @apply px-6 py-4 whitespace-nowrap text-sm text-white;
    }
    
    /* Button Styles */
    .btn-primary {
        @apply inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
    }
    
    .btn-secondary {
        @apply inline-flex items-center px-4 py-2 border border-gray-600 rounded-md shadow-sm text-sm font-medium text-white bg-[#2E324A] hover:bg-[#373B56] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
    }
    
    /* Action Buttons */
    .action-button {
        @apply p-2 rounded-full text-white hover:bg-[#2E324A] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150;
    }
</style>
@endpush
