<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add Product') }}
            </h2>
            <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form wire:submit.prevent="save" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Informations de base -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Basic Information') }}</h3>
                            
                            <!-- Nom -->
                            <div>
                                <x-label for="name" value="{{ __('Name') }}" />
                                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" required />
                                <x-input-error for="name" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div>
                                <x-label for="description" value="{{ __('Description') }}" />
                                <textarea id="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model="description" rows="3"></textarea>
                                <x-input-error for="description" class="mt-2" />
                            </div>

                            <!-- Code-barres -->
                            <div>
                                <x-label for="barcode" value="{{ __('Barcode') }}" />
                                <x-input id="barcode" type="text" class="mt-1 block w-full" wire:model="barcode" />
                                <x-input-error for="barcode" class="mt-2" />
                            </div>

                            <!-- Catégorie -->
                            <div>
                                <x-label for="category_id" value="{{ __('Category') }}" />
                                <select id="category_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model="category_id">
                                    <option value="">{{ __('Select Category') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error for="category_id" class="mt-2" />
                            </div>
                        </div>

                        <!-- Prix et Stock -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Price & Stock') }}</h3>
                            
                            <!-- Prix -->
                            <div>
                                <x-label for="price" value="{{ __('Price') }}" />
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">€</span>
                                    </div>
                                    <x-input id="price" type="number" class="block w-full pl-7" wire:model="price" step="0.01" min="0" required />
                                </div>
                                <x-input-error for="price" class="mt-2" />
                            </div>

                            <!-- Prix de revient -->
                            <div>
                                <x-label for="cost_price" value="{{ __('Cost Price') }}" />
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">€</span>
                                    </div>
                                    <x-input id="cost_price" type="number" class="block w-full pl-7" wire:model="cost_price" step="0.01" min="0" />
                                </div>
                                <x-input-error for="cost_price" class="mt-2" />
                            </div>

                            <!-- TVA -->
                            <div>
                                <x-label for="vat_rate" value="{{ __('VAT Rate') }}" />
                                <select id="vat_rate" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model="vat_rate">
                                    <option value="20">20%</option>
                                    <option value="10">10%</option>
                                    <option value="5.5">5.5%</option>
                                    <option value="2.1">2.1%</option>
                                    <option value="0">0%</option>
                                </select>
                                <x-input-error for="vat_rate" class="mt-2" />
                            </div>

                            <!-- Gestion du stock -->
                            <div>
                                <label class="flex items-center">
                                    <x-checkbox wire:model="track_stock" />
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Track Stock') }}</span>
                                </label>
                            </div>

                            <!-- Alerte stock bas -->
                            <div x-show="$wire.track_stock">
                                <x-label for="min_stock_alert" value="{{ __('Low Stock Alert') }}" />
                                <x-input id="min_stock_alert" type="number" class="mt-1 block w-full" wire:model="min_stock_alert" min="0" />
                                <x-input-error for="min_stock_alert" class="mt-2" />
                            </div>

                            <!-- Stock initial -->
                            <div x-show="$wire.track_stock">
                                <x-label for="initial_stock" value="{{ __('Initial Stock') }}" />
                                <x-input id="initial_stock" type="number" class="mt-1 block w-full" wire:model="initial_stock" min="0" step="0.001" />
                                <x-input-error for="initial_stock" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Options supplémentaires -->
                    <div class="mt-6 space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Additional Options') }}</h3>
                        
                        <div class="flex space-x-6">
                            <label class="flex items-center">
                                <x-checkbox wire:model="is_weighable" />
                                <span class="ml-2 text-sm text-gray-600">{{ __('Weighable Product') }}</span>
                            </label>

                            <label class="flex items-center">
                                <x-checkbox wire:model="has_free_price" />
                                <span class="ml-2 text-sm text-gray-600">{{ __('Free Price') }}</span>
                            </label>

                            <label class="flex items-center">
                                <x-checkbox wire:model="active" />
                                <span class="ml-2 text-sm text-gray-600">{{ __('Active') }}</span>
                            </label>
                        </div>
                    </div>

                    <!-- Image du produit -->
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Product Image') }}</h3>
                        
                        <div class="mt-4 flex items-center space-x-4">
                            @if ($image)
                                <div class="w-32 h-32">
                                    <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover rounded-lg">
                                </div>
                            @endif

                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                <span>{{ __('Upload Image') }}</span>
                                <input type="file" wire:model="image" class="sr-only" accept="image/*">
                            </label>
                        </div>
                        <x-input-error for="image" class="mt-2" />
                    </div>

                    <!-- Boutons d'action -->
                    <div class="mt-6 flex justify-end space-x-3">
                        <x-secondary-button type="button" wire:click="cancel">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-button type="submit" wire:loading.attr="disabled">
                            {{ __('Create Product') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 