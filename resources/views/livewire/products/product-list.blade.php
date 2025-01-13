<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Products') }}
            </h2>
            <a href="{{ route('products.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>{{ __('Create New Product') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#2E324A] overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Search -->
                    <div class="mb-4">
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="search" wire:model.live.debounce.300ms="search"
                                class="block w-full pl-10 text-white bg-[#1B1D29] border-gray-700 rounded-md focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="{{ __('Search products...') }}">
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">{{ __('Name') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">{{ __('Category') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">{{ __('Price') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">{{ __('Stock') }}</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">{{ __('Status') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @forelse($products as $product)
                                    <tr class="hover:bg-[#373B56] transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($product->image)
                                                    <img src="{{ Storage::url($product->image) }}" 
                                                        alt="{{ $product->name }}"
                                                        class="h-10 w-10 rounded-full object-cover">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-[#1B1D29] flex items-center justify-center">
                                                        <i class="fas fa-box text-gray-400"></i>
                                                    </div>
                                                @endif
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-white">
                                                        {{ $product->name }}
                                                    </div>
                                                    @if($product->barcode)
                                                        <div class="text-sm text-gray-400">
                                                            {{ $product->barcode }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-white">
                                                {{ $product->category?->name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="text-sm text-white">
                                                {{ number_format($product->price, 2) }} €
                                            </div>
                                            @if($product->cost_price)
                                                <div class="text-xs text-gray-400">
                                                    {{ number_format($product->cost_price, 2) }} €
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            @if($product->track_stock)
                                                <div class="text-sm {{ $product->current_stock <= $product->alert_quantity ? 'text-red-400' : 'text-white' }}">
                                                    {{ $product->current_stock }}
                                                </div>
                                            @else
                                                <div class="text-sm text-gray-400">
                                                    {{ __('Not tracked') }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($product->is_active) bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ $product->is_active ? __('Active') : __('Inactive') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <button wire:click="edit({{ $product->id }})" class="text-indigo-400 hover:text-indigo-300">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="confirmDelete({{ $product->id }})" class="text-red-400 hover:text-red-300">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-400">
                                            {{ __('No products found.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 