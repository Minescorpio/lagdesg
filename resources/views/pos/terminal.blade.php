<div>
    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Left Side - Products -->
                <div class="w-full md:w-2/3 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-4">
                        <!-- Search and Categories -->
                        <div class="flex flex-col sm:flex-row gap-4 mb-4">
                            <div class="flex-1">
                                <input type="text" 
                                    class="w-full rounded-md border-gray-300" 
                                    placeholder="{{ __('Search products...') }}"
                                    wire:model.debounce.300ms="search">
                            </div>
                            <div class="flex-1">
                                <select class="w-full rounded-md border-gray-300" wire:model="selectedCategory">
                                    <option value="">{{ __('All Categories') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Products Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            @foreach($products as $product)
                                <div class="bg-white border rounded-lg shadow-sm hover:shadow-md transition-shadow cursor-pointer"
                                     wire:click="addToCart({{ $product->id }})">
                                    <div class="aspect-w-1 aspect-h-1">
                                        @if($product->image_path)
                                            <img src="{{ asset('storage/' . $product->image_path) }}" 
                                                 alt="{{ $product->name }}"
                                                 class="object-cover rounded-t-lg">
                                        @else
                                            <div class="flex items-center justify-center bg-gray-100 rounded-t-lg">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-2">
                                        <h3 class="text-sm font-medium truncate">{{ $product->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $product->category->name }}</p>
                                        <div class="mt-1 flex justify-between items-center">
                                            <span class="text-lg font-bold">{{ number_format($product->price, 2) }}</span>
                                            @if($product->track_stock)
                                                <span class="text-xs {{ $product->current_stock <= $product->min_stock_alert ? 'text-red-600' : 'text-green-600' }}">
                                                    {{ $product->current_stock }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Side - Cart -->
                <div class="w-full md:w-1/3 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-4">
                        <h2 class="text-lg font-semibold mb-4">{{ __('Current Cart') }}</h2>
                        
                        <!-- Customer Selection -->
                        <div class="mb-4">
                            <select class="w-full rounded-md border-gray-300" wire:model="selectedCustomer">
                                <option value="">{{ __('Select Customer (Optional)') }}</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->full_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Cart Items -->
                        <div class="space-y-2 mb-4">
                            @forelse($cartItems as $item)
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <h4 class="font-medium">{{ $item->name }}</h4>
                                        <div class="flex items-center gap-2">
                                            <input type="number" 
                                                class="w-20 rounded-md border-gray-300" 
                                                wire:model="cartItems.{{ $loop->index }}.quantity"
                                                min="0.001" 
                                                step="0.001">
                                            <span>x</span>
                                            <span>{{ number_format($item->price, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium">{{ number_format($item->total, 2) }}</span>
                                        <button wire:click="removeFromCart({{ $loop->index }})"
                                            class="text-red-600 hover:text-red-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-gray-500">
                                    {{ __('Cart is empty') }}
                                </div>
                            @endforelse
                        </div>

                        <!-- Cart Summary -->
                        <div class="border-t pt-4 space-y-2">
                            <div class="flex justify-between">
                                <span>{{ __('Subtotal') }}</span>
                                <span>{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>{{ __('Tax') }}</span>
                                <span>{{ number_format($tax, 2) }}</span>
                            </div>
                            <div class="flex justify-between font-bold text-lg">
                                <span>{{ __('Total') }}</span>
                                <span>{{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <!-- Payment Section -->
                        <div class="mt-4 space-y-4">
                            <select class="w-full rounded-md border-gray-300" wire:model="paymentMethod">
                                <option value="">{{ __('Select Payment Method') }}</option>
                                <option value="cash">{{ __('Cash') }}</option>
                                <option value="card">{{ __('Card') }}</option>
                                <option value="bank_transfer">{{ __('Bank Transfer') }}</option>
                            </select>

                            @if($paymentMethod === 'cash')
                                <div class="flex gap-2">
                                    <div class="flex-1">
                                        <input type="number" 
                                            class="w-full rounded-md border-gray-300" 
                                            wire:model="receivedAmount"
                                            placeholder="{{ __('Received Amount') }}">
                                    </div>
                                    <div class="flex-1">
                                        <input type="text" 
                                            class="w-full rounded-md border-gray-300 bg-gray-100" 
                                            value="{{ number_format($change, 2) }}"
                                            readonly
                                            placeholder="{{ __('Change') }}">
                                    </div>
                                </div>
                            @endif

                            <textarea class="w-full rounded-md border-gray-300" 
                                wire:model="notes"
                                rows="2"
                                placeholder="{{ __('Notes (Optional)') }}"></textarea>

                            <div class="flex gap-2">
                                <button wire:click="clearCart"
                                    class="flex-1 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                                    {{ __('Clear') }}
                                </button>
                                <button wire:click="completeSale"
                                    class="flex-1 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                    {{ __('Complete Sale') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 