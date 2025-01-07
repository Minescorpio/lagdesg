<div>
    <form wire:submit="save" class="space-y-8">
        <div class="bg-white shadow-sm rounded-lg">
            <!-- Header -->
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ $productId ? __('Edit Product') : __('New Product') }}
                    </h3>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('products.index') }}" class="btn-secondary">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn-primary">
                            {{ $productId ? __('Update') : __('Create') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Basic Information -->
                    <div class="sm:col-span-2">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">{{ __('Basic Information') }}</h4>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Name -->
                            <div>
                                <label for="name" class="form-label required">{{ __('Name') }}</label>
                                <input type="text" wire:model="name" id="name" class="form-input" required>
                                @error('name') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category_id" class="form-label required">{{ __('Category') }}</label>
                                <select wire:model="category_id" id="category_id" class="form-select" required>
                                    <option value="">{{ __('Select Category') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Barcode -->
                            <div class="relative">
                                <label for="barcode" class="form-label">{{ __('Barcode') }}</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" wire:model="barcode" id="barcode" class="form-input flex-1 rounded-r-none">
                                    <button type="button" wire:click="generateBarcode"
                                        class="relative -ml-px inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                        <i class="fas fa-barcode mr-2"></i>
                                        {{ __('Generate') }}
                                    </button>
                                </div>
                                @error('barcode') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Description -->
                            <div class="sm:col-span-2">
                                <label for="description" class="form-label">{{ __('Description') }}</label>
                                <textarea wire:model="description" id="description" rows="3" class="form-textarea"></textarea>
                                @error('description') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="sm:col-span-2">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">{{ __('Pricing') }}</h4>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                            <!-- Price -->
                            <div>
                                <label for="price" class="form-label required">{{ __('Price') }}</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" wire:model="price" id="price" 
                                        class="form-input pl-7" step="0.01" min="0" required>
                                </div>
                                @error('price') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Cost Price -->
                            <div>
                                <label for="cost_price" class="form-label required">{{ __('Cost Price') }}</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" wire:model="cost_price" id="cost_price" 
                                        class="form-input pl-7" step="0.01" min="0" required>
                                </div>
                                @error('cost_price') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- VAT Rate -->
                            <div>
                                <label for="vat_rate" class="form-label required">{{ __('VAT Rate') }}</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" wire:model="vat_rate" id="vat_rate" 
                                        class="form-input pr-12" step="0.01" min="0" max="100" required>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">%</span>
                                    </div>
                                </div>
                                @error('vat_rate') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Stock Management -->
                    <div class="sm:col-span-2">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">{{ __('Stock Management') }}</h4>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Track Stock Toggle -->
                            <div class="flex items-center">
                                <button type="button" wire:click="$toggle('track_stock')"
                                    class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 {{ $track_stock ? 'bg-blue-600' : 'bg-gray-200' }}"
                                    role="switch" aria-checked="{{ $track_stock ? 'true' : 'false' }}">
                                    <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $track_stock ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                                <span class="ml-3">
                                    <span class="text-sm font-medium text-gray-900">{{ __('Track Stock') }}</span>
                                </span>
                            </div>

                            <!-- Min Stock Alert -->
                            <div x-show="$wire.track_stock">
                                <label for="min_stock_alert" class="form-label">{{ __('Min Stock Alert') }}</label>
                                <input type="number" wire:model="min_stock_alert" id="min_stock_alert" 
                                    class="form-input" min="0" step="1">
                                @error('min_stock_alert') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Product Options -->
                    <div class="sm:col-span-2">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">{{ __('Product Options') }}</h4>
                        <div class="space-y-4">
                            <!-- Weighable Product -->
                            <div class="flex items-center">
                                <button type="button" wire:click="$toggle('is_weighable')"
                                    class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 {{ $is_weighable ? 'bg-blue-600' : 'bg-gray-200' }}"
                                    role="switch" aria-checked="{{ $is_weighable ? 'true' : 'false' }}">
                                    <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $is_weighable ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                                <span class="ml-3">
                                    <span class="text-sm font-medium text-gray-900">{{ __('Weighable Product') }}</span>
                                    <span class="text-sm text-gray-500">{{ __('Product is sold by weight') }}</span>
                                </span>
                            </div>

                            <!-- Free Price -->
                            <div class="flex items-center">
                                <button type="button" wire:click="$toggle('has_free_price')"
                                    class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 {{ $has_free_price ? 'bg-blue-600' : 'bg-gray-200' }}"
                                    role="switch" aria-checked="{{ $has_free_price ? 'true' : 'false' }}">
                                    <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $has_free_price ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                                <span class="ml-3">
                                    <span class="text-sm font-medium text-gray-900">{{ __('Free Price') }}</span>
                                    <span class="text-sm text-gray-500">{{ __('Price can be changed at checkout') }}</span>
                                </span>
                            </div>

                            <!-- Active Status -->
                            <div class="flex items-center">
                                <button type="button" wire:click="$toggle('active')"
                                    class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 {{ $active ? 'bg-blue-600' : 'bg-gray-200' }}"
                                    role="switch" aria-checked="{{ $active ? 'true' : 'false' }}">
                                    <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                                <span class="ml-3">
                                    <span class="text-sm font-medium text-gray-900">{{ __('Active') }}</span>
                                    <span class="text-sm text-gray-500">{{ __('Product is available for sale') }}</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Product Image -->
                    <div class="sm:col-span-2">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">{{ __('Product Image') }}</h4>
                        <div class="flex items-start space-x-4">
                            <!-- Preview -->
                            <div class="flex-shrink-0">
                                @if ($image)
                                    <img src="{{ $image->temporaryUrl() }}" class="h-32 w-32 rounded-lg object-cover">
                                @elseif ($currentImage)
                                    <img src="{{ Storage::url($currentImage) }}" class="h-32 w-32 rounded-lg object-cover">
                                @else
                                    <div class="h-32 w-32 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-image text-4xl text-gray-400"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Upload -->
                            <div class="flex-1">
                                <label for="image" class="form-label">{{ __('Image') }}</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-3"></i>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                <span>{{ __('Upload a file') }}</span>
                                                <input id="image" wire:model="image" type="file" class="sr-only" accept="image/*">
                                            </label>
                                            <p class="pl-1">{{ __('or drag and drop') }}</p>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            {{ __('PNG, JPG, GIF up to 2MB') }}
                                        </p>
                                    </div>
                                </div>
                                @error('image') <span class="form-error">{{ $message }}</span> @enderror

                                @if($currentImage)
                                    <div class="mt-2">
                                        <button type="button" wire:click="deleteImage" 
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-trash text-red-500 mr-2"></i>
                                            {{ __('Delete Image') }}
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<style>
    .form-label {
        @apply block text-sm font-medium text-gray-700;
    }
    .form-label.required::after {
        content: "*";
        @apply text-red-500 ml-1;
    }
    .form-input {
        @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
    }
    .form-select {
        @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
    }
    .form-textarea {
        @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
    }
    .form-error {
        @apply mt-1 text-sm text-red-600;
    }
    .btn-primary {
        @apply inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
    }
    .btn-secondary {
        @apply inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
    }
</style>
@endpush 