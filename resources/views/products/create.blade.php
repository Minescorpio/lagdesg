<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Nouveau produit') }}
            </h2>
            <a href="{{ route('products.index') }}" class="btn-secondary">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                </svg>
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-body">
                    <form wire:submit="save" class="space-y-8">
                        <!-- Basic Information -->
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4">{{ __('Informations de base') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">{{ __('Nom') }} <span class="text-red-500">*</span></label>
                                    <input type="text" id="name" wire:model="name" class="form-input" required>
                                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="barcode" class="form-label">{{ __('Code-barres') }}</label>
                                    <input type="text" id="barcode" wire:model="barcode" class="form-input">
                                    @error('barcode') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="category_id" class="form-label">{{ __('Catégorie') }}</label>
                                    <select id="category_id" wire:model="category_id" class="form-select">
                                        <option value="">{{ __('Sélectionner une catégorie') }}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description" class="form-label">{{ __('Description') }}</label>
                                    <textarea id="description" wire:model="description" class="form-input" rows="3"></textarea>
                                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Price & Stock -->
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4">{{ __('Prix & Stock') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="form-group">
                                    <label for="price" class="form-label">{{ __('Prix de vente') }} <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">€</span>
                                        <input type="number" id="price" wire:model="price" class="form-input pl-8" step="0.01" min="0" required>
                                    </div>
                                    @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="cost_price" class="form-label">{{ __('Prix de revient') }}</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">€</span>
                                        <input type="number" id="cost_price" wire:model="cost_price" class="form-input pl-8" step="0.01" min="0">
                                    </div>
                                    @error('cost_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="vat_rate" class="form-label">{{ __('Taux de TVA') }} <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input type="number" id="vat_rate" wire:model="vat_rate" class="form-input pr-8" step="0.1" min="0" required>
                                        <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">%</span>
                                    </div>
                                    @error('vat_rate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="initial_stock" class="form-label">{{ __('Stock initial') }}</label>
                                    <input type="number" id="initial_stock" wire:model="initial_stock" class="form-input" min="0">
                                    @error('initial_stock') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="alert_stock" class="form-label">{{ __('Alerte stock bas') }}</label>
                                    <input type="number" id="alert_stock" wire:model="alert_stock" class="form-input" min="0">
                                    @error('alert_stock') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Additional Options -->
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4">{{ __('Options supplémentaires') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex items-center">
                                    <input type="checkbox" id="track_stock" wire:model="track_stock" class="form-checkbox">
                                    <label for="track_stock" class="ml-2 text-gray-300">{{ __('Suivre le stock') }}</label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="is_weighable" wire:model="is_weighable" class="form-checkbox">
                                    <label for="is_weighable" class="ml-2 text-gray-300">{{ __('Produit pesable') }}</label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="has_free_price" wire:model="has_free_price" class="form-checkbox">
                                    <label for="has_free_price" class="ml-2 text-gray-300">{{ __('Prix libre') }}</label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="active" wire:model="active" class="form-checkbox">
                                    <label for="active" class="ml-2 text-gray-300">{{ __('Actif') }}</label>
                                </div>
                            </div>
                        </div>

                        <!-- Product Image -->
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4">{{ __('Image du produit') }}</h3>
                            <div class="form-group">
                                <div class="flex items-center justify-center w-full">
                                    <label for="image" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-700 border-dashed rounded-lg cursor-pointer bg-[#2E324A] hover:bg-[#374151] transition-colors duration-200">
                                        @if ($image)
                                            @if(is_string($image))
                                                <img src="{{ $image }}" class="w-auto h-full object-contain">
                                            @else
                                                <img src="{{ $image->temporaryUrl() }}" class="w-auto h-full object-contain">
                                            @endif
                                        @else
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg class="w-8 h-8 mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                </svg>
                                                <p class="mb-2 text-sm text-gray-400">
                                                    <span class="font-semibold">{{ __('Cliquer pour télécharger') }}</span>
                                                    {{ __('ou glisser-déposer') }}
                                                </p>
                                                <p class="text-xs text-gray-400">PNG, JPG ou GIF (MAX. 2MB)</p>
                                            </div>
                                        @endif
                                        <input id="image" type="file" wire:model="image" class="hidden" accept="image/*" />
                                    </label>
                                </div>
                                @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary">
                                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ __('Créer le produit') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 