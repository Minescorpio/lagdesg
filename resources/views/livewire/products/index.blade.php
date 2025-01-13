@php
    $columns = [
        ['key' => 'image', 'label' => __('Image')],
        ['key' => 'name', 'label' => __('Name'), 'sortable' => true],
        ['key' => 'barcode', 'label' => __('Barcode')],
        ['key' => 'category', 'label' => __('Category')],
        ['key' => 'price', 'label' => __('Price'), 'sortable' => true],
        ['key' => 'stock', 'label' => __('Stock'), 'sortable' => true],
        ['key' => 'actions', 'label' => __('Actions')],
    ];
@endphp

<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-white">
                {{ __('Products') }}
            </h2>
            <a href="{{ route('products.create') }}" class="px-4 py-2 text-sm font-medium text-white transition duration-150 ease-in-out bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-700">
                {{ __('Create New Product') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-[#2E324A] shadow-xl sm:rounded-lg">
                <div class="p-6 bg-[#2E324A]">
                    <div class="flex flex-col gap-4 mb-4 md:flex-row md:items-center md:justify-between">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center">
                            <div class="flex-1 md:w-64">
                                <x-input wire:model.live="search" type="search" class="w-full" placeholder="{{ __('Search products...') }}" />
                            </div>
                            <div class="flex-1 md:w-48">
                                <x-select wire:model.live="fournisseurFilter" class="w-full">
                                    <option value="">{{ __('All Suppliers') }}</option>
                                    @foreach($fournisseurs as $fournisseur)
                                        <option value="{{ $fournisseur->id }}">{{ $fournisseur->nom }}</option>
                                    @endforeach
                                </x-select>
                            </div>
                            <div class="flex-1 md:w-48">
                                <x-select wire:model.live="perPage" class="w-full">
                                    <option value="10">10 {{ __('per page') }}</option>
                                    <option value="25">25 {{ __('per page') }}</option>
                                    <option value="50">50 {{ __('per page') }}</option>
                                    <option value="100">100 {{ __('per page') }}</option>
                                </x-select>
                            </div>
                        </div>
                        <div class="flex-1 md:w-64">
                            <x-input wire:model="barcodeInput" wire:keydown.enter="handleBarcodeScan" type="text" class="w-full" placeholder="{{ __('Scan barcode...') }}" />
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead>
                                <tr>
                                    @foreach($columns as $column)
                                        <th class="px-6 py-3 text-left">
                                            <span class="text-xs font-medium tracking-wider text-gray-300 uppercase">
                                                {{ $column['label'] }}
                                            </span>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @forelse($products as $product)
                                    <tr class="hover:bg-[#1B1D29]">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($product->image_path)
                                                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-full">
                                            @else
                                                <div class="w-12 h-12 bg-gray-700 rounded-full"></div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-white">{{ $product->name }}</div>
                                            @if($product->description)
                                                <div class="text-sm text-gray-400">{{ Str::limit($product->description, 50) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-300">{{ $product->barcode }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-300">{{ $product->category?->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-300">{{ number_format($product->price, 2) }} €</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($product->track_stock)
                                                <div class="text-sm {{ $product->stock < $product->alert_quantity ? 'text-red-400' : 'text-gray-300' }}">
                                                    {{ $product->stock }}
                                                </div>
                                            @else
                                                <div class="text-sm text-gray-500">{{ __('Not tracked') }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-300 whitespace-nowrap">
                                            <div class="flex items-center space-x-3">
                                                <a href="{{ route('products.show', $product) }}" class="text-indigo-400 hover:text-indigo-300">
                                                    {{ __('View') }}
                                                </a>
                                                <button wire:click="confirmDelete({{ $product->id }})" class="text-red-400 hover:text-red-300">
                                                    {{ __('Delete') }}
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($columns) }}" class="px-6 py-4 text-sm text-center text-gray-400 whitespace-nowrap">
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

    <!-- Delete Confirmation Modal -->
    <x-dialog-modal wire:model.live="showDeleteModal">
        <x-slot name="title">
            {{ __('Delete Product') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete this product? This action cannot be undone.') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showDeleteModal', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ml-3" wire:click="deleteProduct" wire:loading.attr="disabled">
                {{ __('Delete Product') }}
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Create Product Modal -->
    <x-dialog-modal wire:model.live="showCreateForm" maxWidth="4xl">
        <x-slot name="title">
            {{ __('Create New Product') }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 gap-6 mt-4 md:grid-cols-2">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-white">{{ __('Basic Information') }}</h3>
                    
                    <div>
                        <x-label for="name" value="{{ __('Name') }}" />
                        <x-input id="name" type="text" class="block w-full mt-1" wire:model="name" />
                        <x-input-error for="name" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="description" value="{{ __('Description') }}" />
                        <x-textarea id="description" class="block w-full mt-1" wire:model="description" />
                        <x-input-error for="description" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="barcode" value="{{ __('Barcode') }}" />
                        <x-input id="barcode" type="text" class="block w-full mt-1" wire:model="barcode" />
                        <x-input-error for="barcode" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="category_id" value="{{ __('Category') }}" />
                        <x-select id="category_id" class="block w-full mt-1" wire:model="category_id">
                            <option value="">{{ __('Select a category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error for="category_id" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="fournisseur_id" value="{{ __('Supplier') }}" />
                        <x-select id="fournisseur_id" class="block w-full mt-1" wire:model="fournisseur_id">
                            <option value="">{{ __('Select a supplier') }}</option>
                            @foreach($fournisseurs as $fournisseur)
                                <option value="{{ $fournisseur->id }}">{{ $fournisseur->nom }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error for="fournisseur_id" class="mt-2" />
                    </div>
                </div>

                <!-- Price & Stock -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-white">{{ __('Price & Stock') }}</h3>
                    
                    <div>
                        <x-label for="price" value="{{ __('Selling Price') }}" />
                        <x-input id="price" type="number" step="0.01" class="block w-full mt-1" wire:model="price" />
                        <x-input-error for="price" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="cost_price" value="{{ __('Cost Price') }}" />
                        <x-input id="cost_price" type="number" step="0.01" class="block w-full mt-1" wire:model="cost_price" />
                        <x-input-error for="cost_price" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="vat_rate" value="{{ __('VAT Rate') }}" />
                        <x-input id="vat_rate" type="number" step="0.01" class="block w-full mt-1" wire:model="vat_rate" />
                        <x-input-error for="vat_rate" class="mt-2" />
                    </div>

                    <div class="flex items-center mt-4">
                        <x-checkbox id="track_stock" wire:model="track_stock" />
                        <x-label for="track_stock" class="ml-2" value="{{ __('Track Stock') }}" />
                    </div>

                    @if($track_stock)
                        <div>
                            <x-label for="alert_quantity" value="{{ __('Alert Quantity') }}" />
                            <x-input id="alert_quantity" type="number" class="block w-full mt-1" wire:model="alert_quantity" />
                            <x-input-error for="alert_quantity" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="initial_stock" value="{{ __('Initial Stock') }}" />
                            <x-input id="initial_stock" type="number" class="block w-full mt-1" wire:model="initial_stock" />
                            <x-input-error for="initial_stock" class="mt-2" />
                        </div>
                    @endif
                </div>

                <!-- Additional Options -->
                <div class="space-y-4 md:col-span-2">
                    <h3 class="text-lg font-medium text-white">{{ __('Additional Options') }}</h3>
                    
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div class="flex items-center">
                            <x-checkbox id="weighable" wire:model="weighable" />
                            <x-label for="weighable" class="ml-2" value="{{ __('Weighable Product') }}" />
                        </div>

                        <div class="flex items-center">
                            <x-checkbox id="free_price" wire:model="free_price" />
                            <x-label for="free_price" class="ml-2" value="{{ __('Free Price') }}" />
                        </div>

                        <div class="flex items-center">
                            <x-checkbox id="active" wire:model="active" />
                            <x-label for="active" class="ml-2" value="{{ __('Active') }}" />
                        </div>
                    </div>

                    <div>
                        <x-label for="image" value="{{ __('Product Image') }}" />
                        <input type="file" id="image" wire:model="image" class="block w-full mt-1 text-sm text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500" />
                        <x-input-error for="image" class="mt-2" />
                        
                        @if ($image)
                            <div class="mt-2">
                                <img src="{{ $image->temporaryUrl() }}" class="w-32 h-32 rounded-lg">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="cancelCreate" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ml-3" wire:click="save" wire:loading.attr="disabled">
                {{ __('Create Product') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
