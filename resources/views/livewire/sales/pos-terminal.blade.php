<div class="h-screen bg-gray-100 flex overflow-hidden">
    <!-- Left Side - Products -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Search and Filters -->
        <div class="bg-white border-b border-gray-200 p-4">
            <div class="flex space-x-4">
                <!-- Search -->
                <div class="flex-1">
                    <div class="relative">
                        <input type="search" wire:model.live.debounce.300ms="search"
                            class="form-input pl-10 w-full"
                            placeholder="{{ __('Search products...') }}">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="w-48">
                    <select wire:model.live="selectedCategory" class="form-select w-full">
                        <option value="">{{ __('All Categories') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Favorites Toggle -->
                <button type="button" wire:click="$toggle('showFavorites')"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium {{ $showFavorites ? 'text-blue-600 border-blue-600' : 'text-gray-700' }} bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-star mr-2"></i>
                    {{ __('Favorites') }}
                </button>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="flex-1 overflow-y-auto p-4">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                @forelse($products as $product)
                    <button wire:click="addToCart({{ $product->id }})"
                        class="bg-white rounded-lg shadow hover:shadow-md transition-shadow duration-200 overflow-hidden focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <div class="aspect-w-1 aspect-h-1 bg-gray-200">
                            @if($product->image_path)
                                <img src="{{ Storage::url($product->image_path) }}" 
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-box text-4xl text-gray-400"></i>
                                </div>
                            @endif
                            @if($product->track_stock && $product->current_stock <= $product->min_stock_alert)
                                <div class="absolute top-2 right-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ $product->current_stock }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="text-sm font-medium text-white truncate">{{ $product->name }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ money($product->price) }}</p>
                        </div>
                    </button>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-12">
                        <i class="fas fa-search text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-white">{{ __('No products found') }}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ __('Try adjusting your search or filters') }}</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Right Side - Cart -->
    <div class="w-96 flex flex-col bg-white border-l border-gray-200">
        <!-- Customer Selection -->
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-medium text-white">{{ __('Current Sale') }}</h2>
                @if($customer)
                    <button type="button" wire:click="setCustomer(null)"
                        class="text-sm text-red-600 hover:text-red-800">
                        <i class="fas fa-times"></i>
                    </button>
                @endif
            </div>

            @if($customer)
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-user-circle text-2xl text-blue-600"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">
                                {{ $customer->first_name }} {{ $customer->last_name }}
                            </h3>
                            <div class="mt-1 text-sm text-blue-600">
                                <p>{{ __('Points') }}: {{ number_format($customer->loyalty_points) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <button type="button" 
                    x-data
                    x-on:click="$dispatch('open-modal', 'select-customer')"
                    class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-user-plus mr-2"></i>
                    {{ __('Select Customer') }}
                </button>
            @endif
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4">
            @forelse($cart as $index => $item)
                <div class="flex items-center">
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-white truncate">{{ $item['name'] }}</h4>
                        <div class="mt-1 flex items-center text-sm text-gray-500">
                            <span>{{ money($item['unit_price']) }} × </span>
                            <input type="number" wire:model.live="cart.{{ $index }}.quantity"
                                class="form-input w-20 mx-2" min="0" step="{{ $item['is_weighable'] ? '0.001' : '1' }}">
                            <span>= {{ money($item['total']) }}</span>
                        </div>
                    </div>
                    <button wire:click="removeFromCart({{ $index }})"
                        class="ml-4 text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            @empty
                <div class="text-center py-12">
                    <i class="fas fa-shopping-cart text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-sm font-medium text-white">{{ __('Cart is empty') }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ __('Add some products to get started') }}</p>
                </div>
            @endforelse
        </div>

        <!-- Cart Summary -->
        <div class="border-t border-gray-200 p-4 bg-gray-50">
            <div class="space-y-4">
                <!-- Subtotal -->
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">{{ __('Subtotal') }}</span>
                    <span class="text-gray-900">{{ money($subtotal) }}</span>
                </div>

                <!-- Tax -->
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">{{ __('Tax') }}</span>
                    <span class="text-gray-900">{{ money($taxAmount) }}</span>
                </div>

                <!-- Loyalty Points -->
                @if($customer)
                    <div class="border-t border-gray-200 pt-4">
                        <label for="loyalty_points" class="block text-sm font-medium text-gray-700">
                            {{ __('Use Loyalty Points') }}
                        </label>
                        <div class="mt-1">
                            <input type="number" wire:model.live="loyaltyPointsToUse"
                                max="{{ $customer->loyalty_points }}"
                                class="form-input" min="0">
                        </div>
                        @if($discountAmount > 0)
                            <div class="mt-2 flex justify-between text-sm">
                                <span class="text-gray-500">{{ __('Discount') }}</span>
                                <span class="text-green-600">-{{ money($discountAmount) }}</span>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Total -->
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-base font-medium text-white">{{ __('Total') }}</span>
                        <span class="text-2xl font-bold text-blue-600">{{ money($totalAmount) }}</span>
                    </div>
                </div>

                <!-- Payment Method -->
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700">
                        {{ __('Payment Method') }}
                    </label>
                    <select wire:model.live="paymentMethod" class="form-select mt-1">
                        <option value="cash">{{ __('Cash') }}</option>
                        <option value="card">{{ __('Card') }}</option>
                        <option value="bank_transfer">{{ __('Bank Transfer') }}</option>
                        @if($customer && $customer->loyalty_points >= $totalAmount)
                            <option value="loyalty_points">{{ __('Loyalty Points') }}</option>
                        @endif
                        <option value="mixed">{{ __('Mixed') }}</option>
                    </select>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">
                        {{ __('Notes') }}
                    </label>
                    <textarea wire:model="notes" rows="2" class="form-textarea mt-1"></textarea>
                </div>

                <!-- Complete Sale Button -->
                <button wire:click="processSale"
                    @class([
                        'w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500',
                        'bg-blue-600 hover:bg-blue-700' => !empty($cart),
                        'bg-gray-400 cursor-not-allowed' => empty($cart),
                    ])
                    @disabled(empty($cart))>
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ __('Complete Sale') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Customer Selection Modal -->
    <x-modal name="select-customer">
        <div class="p-6">
            <h2 class="text-lg font-medium text-white mb-4">{{ __('Select Customer') }}</h2>
            <div class="space-y-4">
                <!-- Customer Search -->
                <div>
                    <label for="customer_search" class="sr-only">{{ __('Search customers') }}</label>
                    <div class="relative">
                        <input type="search" wire:model.live.debounce.300ms="customerSearch"
                            class="form-input pl-10"
                            placeholder="{{ __('Search customers...') }}">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Customer List -->
                <div class="space-y-2 max-h-96 overflow-y-auto">
                    @foreach($searchedCustomers as $searchedCustomer)
                        <button wire:click="setCustomer({{ $searchedCustomer->id }})"
                            class="w-full flex items-center p-4 border rounded-lg hover:bg-gray-50">
                            <div class="flex-shrink-0">
                                <i class="fas fa-user-circle text-2xl text-gray-400"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="text-sm font-medium text-white">
                                    {{ $searchedCustomer->first_name }} {{ $searchedCustomer->last_name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $searchedCustomer->email }}
                                </div>
                            </div>
                            <div class="ml-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ number_format($searchedCustomer->loyalty_points) }} {{ __('points') }}
                                </span>
                            </div>
                        </button>
                    @endforeach
                </div>

                <!-- New Customer Button -->
                <div class="mt-6 flex justify-end">
                    <a href="{{ route('customers.create') }}" class="btn-primary">
                        <i class="fas fa-user-plus mr-2"></i>
                        {{ __('New Customer') }}
                    </a>
                </div>
            </div>
        </div>
    </x-modal>
</div>

@push('styles')
<style>
    .form-label {
        @apply block text-sm font-medium text-gray-700;
    }
    .form-input {
        @apply block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
    }
    .form-select {
        @apply block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
    }
    .form-textarea {
        @apply block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
    }
    .btn-primary {
        @apply inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
    }
</style>
@endpush 