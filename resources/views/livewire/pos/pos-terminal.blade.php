<div class="h-screen bg-gray-100">
    <!-- Main Layout -->
    <div class="flex h-full">
        <!-- Left Side - Products -->
        <div class="w-2/3 h-full flex flex-col bg-white">
            <!-- Header -->
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-2xl font-bold text-gray-900">{{ __('POS Terminal') }}</h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">{{ __('Cashier') }}: {{ auth()->user()->name }}</span>
                        <span class="text-sm text-gray-500">{{ now()->format('d M Y H:i') }}</span>
                    </div>
                </div>
                
                <!-- Search and Filters -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-2">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="search" wire:model.live.debounce.300ms="search" 
                                class="form-input pl-10" 
                                placeholder="{{ __('Search products by name, SKU or scan barcode...') }}">
                        </div>
                    </div>
                    <div>
                        <select wire:model.live="selectedCategory" class="form-select">
                            <option value="">{{ __('All Categories') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="flex-1 overflow-y-auto p-4">
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @forelse($products as $product)
                        <button wire:click="addToCart({{ $product->id }})" 
                            class="relative bg-white border rounded-lg p-4 hover:shadow-lg transition-shadow duration-200 {{ $product->stock->quantity <= 0 ? 'opacity-50' : '' }}"
                            {{ $product->stock->quantity <= 0 ? 'disabled' : '' }}>
                            <!-- Product Image -->
                            <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-lg bg-gray-200 mb-4">
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" 
                                        alt="{{ $product->name }}"
                                        class="object-cover object-center">
                                @else
                                    <div class="flex items-center justify-center h-full">
                                        <i class="fas fa-box text-gray-400 text-3xl"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Product Info -->
                            <div class="text-left">
                                <h3 class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</h3>
                                <p class="mt-1 text-sm text-gray-500">SKU: {{ $product->sku }}</p>
                                <div class="mt-2 flex items-center justify-between">
                                    <span class="text-lg font-bold text-gray-900">
                                        {{ money($product->price) }}
                                    </span>
                                    <span class="text-sm {{ $product->stock->quantity <= $product->stock->alert_level ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $product->stock->quantity }} {{ __('in stock') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Quick Add Overlay -->
                            @if($product->stock->quantity > 0)
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-200 bg-black bg-opacity-50 rounded-lg">
                                <div class="bg-white p-2 rounded-lg shadow-lg">
                                    <button wire:click.stop="quickAdd({{ $product->id }})" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-plus-circle text-2xl"></i>
                                    </button>
                                </div>
                            </div>
                            @endif
                        </button>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-12">
                            <i class="fas fa-search text-gray-400 text-5xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900">{{ __('No products found') }}</h3>
                            <p class="mt-1 text-gray-500">{{ __('Try adjusting your search or filters') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="border-t border-gray-200 px-4 py-3">
                    {{ $products->links() }}
                </div>
            @endif
        </div>

        <!-- Right Side - Cart -->
        <div class="w-1/3 h-full flex flex-col bg-gray-50 border-l border-gray-200">
            <!-- Customer Selection -->
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center space-x-4">
                    <div class="flex-1">
                        <select wire:model.live="selectedCustomer" class="form-select">
                            <option value="">{{ __('Walk-in Customer') }}</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button wire:click="$dispatch('openModal', 'create-customer')" class="btn-secondary">
                        <i class="fas fa-user-plus"></i>
                    </button>
                </div>

                @if($selectedCustomer && $customer = $customers->find($selectedCustomer))
                    <div class="mt-2 p-2 bg-blue-50 rounded-md">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-blue-700">{{ __('Loyalty Points') }}:</span>
                            <span class="font-medium">{{ number_format($customer->loyalty_points) }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto p-4">
                @forelse($cart as $item)
                    <div class="flex items-center justify-between bg-white p-4 rounded-lg mb-2 shadow-sm">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                @if($item['product']->image)
                                    <img src="{{ Storage::url($item['product']->image) }}" 
                                        alt="{{ $item['product']->name }}"
                                        class="h-12 w-12 rounded-lg object-cover">
                                @else
                                    <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-box text-gray-400"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $item['product']->name }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ money($item['product']->price) }} × {{ $item['quantity'] }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-medium text-gray-900">
                                    {{ money($item['product']->price * $item['quantity']) }}
                                </span>
                                <button wire:click="removeFromCart({{ $item['product']->id }})" 
                                    class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-gray-500">
                        <i class="fas fa-shopping-cart text-5xl mb-4"></i>
                        <p class="text-lg font-medium">{{ __('Your cart is empty') }}</p>
                        <p class="text-sm">{{ __('Add some products to get started') }}</p>
                    </div>
                @endforelse
            </div>

            <!-- Cart Summary -->
            <div class="border-t border-gray-200 p-4 bg-white">
                <div class="space-y-4">
                    <!-- Subtotal -->
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">{{ __('Subtotal') }}</span>
                        <span class="font-medium">{{ money($subtotal) }}</span>
                    </div>

                    <!-- Tax -->
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">{{ __('Tax') }} ({{ $taxRate }}%)</span>
                        <span class="font-medium">{{ money($tax) }}</span>
                    </div>

                    <!-- Discount -->
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <input type="number" wire:model.live="discount" 
                                class="form-input" 
                                placeholder="{{ __('Discount amount') }}">
                        </div>
                        <select wire:model.live="discountType" class="form-select ml-2 w-24">
                            <option value="amount">$</option>
                            <option value="percentage">%</option>
                        </select>
                    </div>

                    <!-- Total -->
                    <div class="flex justify-between items-center text-lg font-bold">
                        <span>{{ __('Total') }}</span>
                        <span>{{ money($total) }}</span>
                    </div>

                    <!-- Payment Methods -->
                    <div class="grid grid-cols-2 gap-2">
                        <button wire:click="setPaymentMethod('cash')" 
                            class="payment-method-btn {{ $paymentMethod === 'cash' ? 'active' : '' }}">
                            <i class="fas fa-money-bill-wave mr-2"></i>
                            {{ __('Cash') }}
                        </button>
                        <button wire:click="setPaymentMethod('card')" 
                            class="payment-method-btn {{ $paymentMethod === 'card' ? 'active' : '' }}">
                            <i class="fas fa-credit-card mr-2"></i>
                            {{ __('Card') }}
                        </button>
                    </div>

                    <!-- Process Payment -->
                    <button wire:click="processPayment" 
                        class="btn-primary w-full {{ empty($cart) ? 'opacity-50 cursor-not-allowed' : '' }}"
                        {{ empty($cart) ? 'disabled' : '' }}>
                        <i class="fas fa-cash-register mr-2"></i>
                        {{ __('Process Payment') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Add Modal -->
    <div x-data="{ show: false, quantity: 1 }"
        x-show="show"
        x-on:quick-add-modal.window="show = true"
        x-on:close-modal.window="show = false"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="relative bg-white rounded-lg max-w-md w-full">
                <div class="p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Add to Cart') }}</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700">
                                {{ __('Quantity') }}
                            </label>
                            <input type="number" x-model="quantity" min="1" 
                                class="form-input mt-1"
                                placeholder="{{ __('Enter quantity') }}">
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button x-on:click="show = false" class="btn-secondary">
                                {{ __('Cancel') }}
                            </button>
                            <button x-on:click="$wire.addToCart($wire.quickAddProductId, quantity); show = false" 
                                class="btn-primary">
                                {{ __('Add') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .payment-method-btn {
        @apply flex items-center justify-center px-4 py-2 border rounded-md text-sm font-medium transition-colors duration-200;
    }
    .payment-method-btn:not(.active) {
        @apply border-gray-300 text-gray-700 bg-white hover:bg-gray-50;
    }
    .payment-method-btn.active {
        @apply border-blue-600 text-blue-600 bg-blue-50;
    }
    .form-input {
        @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
    }
    .form-select {
        @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
    }
    .btn-primary {
        @apply inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
    }
    .btn-secondary {
        @apply inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
    }
</style>
@endpush 