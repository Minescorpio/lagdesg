<div>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-[#2E324A] shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <form wire:submit.prevent="save" class="space-y-8">
                        <!-- Basic Information -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-medium text-white">{{ __('Basic Information') }}</h3>
                            
                            <!-- Name -->
                            <div>
                                <x-label for="name" value="{{ __('Product Name') }}" />
                                <x-input id="name" wire:model="name" type="text" class="mt-1 block w-full" required />
                                <x-input-error for="name" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div>
                                <x-label for="description" value="{{ __('Description') }}" />
                                <x-textarea id="description" wire:model="description" rows="4" class="mt-1 block w-full"></x-textarea>
                                <x-input-error for="description" class="mt-2" />
                            </div>

                            <!-- Price -->
                            <div>
                                <x-label for="price" value="{{ __('Price') }}" />
                                <div class="relative">
                                    <x-input id="price" wire:model="price" type="number" step="0.01" class="mt-1 block w-full pr-8" required />
                                    <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-white">€</span>
                                </div>
                                <x-input-error for="price" class="mt-2" />
                            </div>

                            <!-- Category -->
                            <div>
                                <x-label for="category_id" value="{{ __('Category') }}" />
                                <x-select wire:model="category_id" id="category_id" class="mt-1 block w-full" required>
                                    <option value="">{{ __('Select a category') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </x-select>
                                <x-input-error for="category_id" class="mt-2" />
                            </div>

                            <!-- Supplier -->
                            <div>
                                <x-label for="fournisseur_id" value="{{ __('Supplier') }}" />
                                <x-select wire:model="fournisseur_id" id="fournisseur_id" class="mt-1 block w-full">
                                    <option value="">{{ __('Select a supplier') }}</option>
                                    @foreach($fournisseurs as $fournisseur)
                                        <option value="{{ $fournisseur->id }}">{{ $fournisseur->nom }}</option>
                                    @endforeach
                                </x-select>
                                <x-input-error for="fournisseur_id" class="mt-2" />
                            </div>
                        </div>

                        <!-- Stock Management -->
                        <div class="space-y-6 pt-8">
                            <h3 class="text-lg font-medium text-white border-t border-gray-700 pt-8">{{ __('Stock Management') }}</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Initial Stock -->
                                <div>
                                    <x-label for="initial_stock" value="{{ __('Initial Stock') }}" />
                                    <x-input id="initial_stock" wire:model="initial_stock" type="number" class="mt-1 block w-full" required />
                                    <x-input-error for="initial_stock" class="mt-2" />
                                </div>

                                <!-- Alert Threshold -->
                                <div>
                                    <x-label for="alert_quantity" value="{{ __('Alert Threshold') }}" />
                                    <x-input id="alert_quantity" wire:model="alert_quantity" type="number" class="mt-1 block w-full" />
                                    <x-input-error for="alert_quantity" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Status and Options -->
                        <div class="space-y-6 pt-8">
                            <h3 class="text-lg font-medium text-white border-t border-gray-700 pt-8">{{ __('Status and Options') }}</h3>
                            
                            <div class="space-y-4">
                                <!-- Status -->
                                <label class="flex items-center">
                                    <x-checkbox wire:model="active" />
                                    <span class="ml-2 text-sm text-white">{{ __('Active Product') }}</span>
                                </label>

                                <!-- Weighable -->
                                <label class="flex items-center">
                                    <x-checkbox wire:model="weighable" />
                                    <span class="ml-2 text-sm text-white">{{ __('Weighable Product') }}</span>
                                </label>

                                <!-- Free Price -->
                                <label class="flex items-center">
                                    <x-checkbox wire:model="free_price" />
                                    <span class="ml-2 text-sm text-white">{{ __('Free Price') }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Images -->
                        <div class="space-y-6 pt-8">
                            <h3 class="text-lg font-medium text-white border-t border-gray-700 pt-8">{{ __('Images') }}</h3>
                            
                            <div class="flex items-center justify-center">
                                <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-600 border-dashed rounded-lg cursor-pointer bg-[#1B1D29] hover:bg-[#2E324A] transition-colors duration-150">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <p class="mb-2 text-sm text-white">{{ __('Click or drag and drop') }}</p>
                                        <p class="text-xs text-gray-400">PNG, JPG, GIF (MAX. 2MB)</p>
                                    </div>
                                    <input type="file" wire:model="image" class="hidden" accept="image/*">
                                </label>
                            </div>
                            <x-input-error for="image" class="mt-2" />
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-4 pt-8 border-t border-gray-700">
                            <x-button type="button" wire:click="cancel" class="bg-gray-600 hover:bg-gray-500">
                                {{ __('Cancel') }}
                            </x-button>
                            <x-button type="submit" class="bg-indigo-600 hover:bg-indigo-500">
                                {{ __('Create Product') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 