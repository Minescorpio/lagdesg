<div>
    <form wire:submit="save" class="space-y-8">
        <div class="bg-white shadow-sm rounded-lg">
            <!-- Header -->
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-white">
                        {{ $stockId ? __('Edit Stock Record') : __('New Stock Record') }}
                    </h3>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('stock.index') }}" class="btn-secondary">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn-primary">
                            {{ $stockId ? __('Update') : __('Create') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 gap-6">
                    <!-- Product Selection -->
                    <div class="sm:col-span-2">
                        <label for="product_id" class="form-label required">{{ __('Product') }}</label>
                        <div class="mt-1">
                            <select wire:model="product_id" id="product_id" class="form-select" required>
                                <option value="">{{ __('Select a product') }}</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }} (SKU: {{ $product->sku }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('product_id') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <!-- Current Stock Information -->
                    @if($stockId && $currentStock)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-white mb-3">{{ __('Current Stock Information') }}</h4>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            <div>
                                <span class="block text-sm text-gray-500">{{ __('Current Quantity') }}</span>
                                <span class="block mt-1 text-lg font-medium text-white">{{ $currentStock->quantity }}</span>
                            </div>
                            <div>
                                <span class="block text-sm text-gray-500">{{ __('Alert Level') }}</span>
                                <span class="block mt-1 text-lg font-medium text-white">{{ $currentStock->alert_level }}</span>
                            </div>
                            <div>
                                <span class="block text-sm text-gray-500">{{ __('Status') }}</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-2
                                    {{ $currentStock->quantity <= 0 ? 'bg-red-100 text-red-800' : 
                                       ($currentStock->quantity <= $currentStock->alert_level ? 'bg-yellow-100 text-yellow-800' : 
                                        'bg-green-100 text-green-800') }}">
                                    {{ $currentStock->quantity <= 0 ? __('Out of Stock') : 
                                       ($currentStock->quantity <= $currentStock->alert_level ? __('Low Stock') : __('In Stock')) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Stock Adjustment -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Quantity -->
                        <div>
                            <label for="quantity" class="form-label required">
                                {{ $stockId ? __('Quantity Adjustment') : __('Initial Quantity') }}
                            </label>
                            <div class="mt-1">
                                <input type="number" wire:model="quantity" id="quantity" 
                                    class="form-input" min="0" step="1" required>
                                @if($stockId)
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ __('Enter a positive number to add stock or a negative number to remove stock') }}
                                    </p>
                                @endif
                            </div>
                            @error('quantity') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <!-- Alert Level -->
                        <div>
                            <label for="alert_level" class="form-label required">{{ __('Alert Level') }}</label>
                            <div class="mt-1">
                                <input type="number" wire:model="alert_level" id="alert_level" 
                                    class="form-input" min="0" step="1" required>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ __('You will be notified when stock falls below this level') }}
                                </p>
                            </div>
                            @error('alert_level') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="form-label">{{ __('Notes') }}</label>
                        <div class="mt-1">
                            <textarea wire:model="notes" id="notes" rows="3" class="form-textarea"></textarea>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ __('Add any additional information about this stock adjustment') }}
                            </p>
                        </div>
                        @error('notes') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <!-- Location -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="location" class="form-label">{{ __('Storage Location') }}</label>
                            <div class="mt-1">
                                <input type="text" wire:model="location" id="location" class="form-input">
                            </div>
                            @error('location') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="batch_number" class="form-label">{{ __('Batch Number') }}</label>
                            <div class="mt-1">
                                <input type="text" wire:model="batch_number" id="batch_number" class="form-input">
                            </div>
                            @error('batch_number') <span class="form-error">{{ $message }}</span> @enderror
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