<div class="p-6 lg:p-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Products Section -->
        <div class="lg:col-span-8">
            <div class="mb-6">
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <x-input type="text" wire:model.live="search" placeholder="{{ __('Search products...') }}" class="w-full" />
                    </div>
                    <div>
                        <x-select wire:model.live="categoryFilter">
                            <option value="">{{ __('All Categories') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </x-select>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse($products as $product)
                    <div wire:key="product-{{ $product->id }}" class="bg-white dark:bg-gray-700 rounded-lg shadow p-4">
                        <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-lg bg-gray-200 mb-4">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover object-center">
                            @else
                                <div class="flex items-center justify-center h-full bg-gray-100 dark:bg-gray-600">
                                    <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $product->name }}</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ money($product->price) }}</p>
                        <button wire:click="addToCart({{ $product->id }})" class="mt-4 w-full bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            {{ __('Add to Cart') }}
                        </button>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 text-gray-500 dark:text-gray-400">
                        {{ __('No products found.') }}
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>

        <!-- Cart Section -->
        <div class="lg:col-span-4">
            <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">{{ __('Current Cart') }}</h2>

                @if(count($cart))
                    <div class="space-y-4 mb-6">
                        @foreach($cart as $item)
                            <div wire:key="cart-{{ $item['id'] }}" class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item['name'] }}</h3>
                                    <div class="flex items-center mt-1">
                                        <button wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                            </svg>
                                        </button>
                                        <span class="mx-2 text-gray-700 dark:text-gray-300">{{ $item['quantity'] }}</span>
                                        <button wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </button>
                                        <span class="ml-4 text-gray-500 dark:text-gray-400">{{ money($item['price']) }}</span>
                                    </div>
                                </div>
                                <button wire:click="removeFromCart({{ $item['id'] }})" class="text-red-500 hover:text-red-700">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-600 pt-4 space-y-4">
                        <div class="flex justify-between text-base font-medium text-gray-900 dark:text-gray-100">
                            <p>{{ __('Subtotal') }}</p>
                            <p>{{ money($this->subtotal) }}</p>
                        </div>
                        <div class="flex justify-between text-base font-medium text-gray-900 dark:text-gray-100">
                            <p>{{ __('Tax') }} ({{ $taxRate }}%)</p>
                            <p>{{ money($this->tax) }}</p>
                        </div>
                        <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-gray-100">
                            <p>{{ __('Total') }}</p>
                            <p>{{ money($this->total) }}</p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-4">
                        <div>
                            <x-label for="customer_id" value="{{ __('Customer') }}" />
                            <x-select id="customer_id" wire:model="customerId" class="w-full">
                                <option value="">{{ __('Walk-in Customer') }}</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </x-select>
                        </div>

                        <div>
                            <x-label for="payment_method" value="{{ __('Payment Method') }}" />
                            <x-select id="payment_method" wire:model="paymentMethod" class="w-full">
                                <option value="cash">{{ __('Cash') }}</option>
                                <option value="card">{{ __('Card') }}</option>
                                <option value="transfer">{{ __('Bank Transfer') }}</option>
                            </x-select>
                        </div>

                        @if($paymentMethod === 'cash')
                            <div>
                                <x-label for="amount_received" value="{{ __('Amount Received') }}" />
                                <x-input id="amount_received" type="number" step="0.01" wire:model="amountReceived" class="w-full" />
                                @if($this->change > 0)
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('Change') }}: {{ money($this->change) }}
                                    </p>
                                @endif
                            </div>
                        @endif

                        <div>
                            <x-label for="notes" value="{{ __('Notes') }}" />
                            <x-textarea id="notes" wire:model="notes" class="w-full" rows="2"></x-textarea>
                        </div>

                        <x-button wire:click="processSale" class="w-full justify-center" wire:loading.attr="disabled">
                            <span wire:loading.remove>{{ __('Complete Sale') }}</span>
                            <span wire:loading>{{ __('Processing...') }}</span>
                        </x-button>

                        <x-button wire:click="clearCart" class="w-full justify-center bg-gray-600 hover:bg-gray-700">
                            {{ __('Clear Cart') }}
                        </x-button>
                    </div>
                @else
                    <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                        {{ __('Cart is empty') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <x-dialog-modal wire:model.live="showSuccessModal">
        <x-slot name="title">
            {{ __('Sale Completed') }}
        </x-slot>

        <x-slot name="content">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <p class="mt-4 text-lg text-gray-700 dark:text-gray-300">
                    {{ __('Sale has been completed successfully!') }}
                </p>
                @if($lastSaleId)
                    <div class="mt-6 flex justify-center space-x-4">
                        <a href="{{ route('pos.sales.show', $lastSaleId) }}" class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
                            {{ __('View Sale') }}
                        </a>
                        <a href="{{ route('pos.sales.receipt', $lastSaleId) }}" target="_blank" class="text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300">
                            {{ __('Print Receipt') }}
                        </a>
                    </div>
                @endif
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-button wire:click="$set('showSuccessModal', false)">
                {{ __('Continue Selling') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
