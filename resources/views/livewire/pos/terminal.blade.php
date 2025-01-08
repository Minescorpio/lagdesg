<div>
    <div class="flex h-screen bg-[#1B1D29]">
        <!-- Left Side -->
        <div class="flex-1 flex flex-col">
            <!-- Stats Bar -->
            <div class="grid grid-cols-4 gap-4 p-4">
                <!-- Cart Total -->
                <div class="stats-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="stats-label">{{ __('Total du panier') }}</p>
                            <p class="stats-value">{{ number_format($cartTotal, 2, ',', ' ') }} €</p>
                        </div>
                        <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Items in Cart -->
                <div class="stats-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="stats-label">{{ __('Articles dans le panier') }}</p>
                            <p class="stats-value">{{ $cartItemsCount }}</p>
                        </div>
                        <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                </div>

                <!-- Selected Customer -->
                <div class="stats-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="stats-label">{{ __('Client sélectionné') }}</p>
                            <p class="stats-value">{{ $selectedCustomer ? $selectedCustomer->name : __('Aucun') }}</p>
                        </div>
                        <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>

                <!-- Last Sale -->
                <div class="stats-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="stats-label">{{ __('Dernière vente') }}</p>
                            <p class="stats-value">{{ $lastSale ? number_format($lastSale->total_amount, 2, ',', ' ') . ' €' : '-' }}</p>
                        </div>
                        <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="flex-1 p-4 overflow-y-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($products as $product)
                    <button wire:click="addToCart({{ $product->id }})" class="bg-[#2E324A] p-4 rounded-lg hover:bg-[#3E4366] transition-colors duration-150">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-white font-medium">{{ $product->name }}</h3>
                            <span class="text-gray-400 text-sm">{{ $product->code }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-white text-lg font-semibold">{{ number_format($product->price, 2, ',', ' ') }} €</span>
                            <span class="text-gray-400 text-sm">Stock: {{ $product->stock_quantity }}</span>
                        </div>
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Side -->
        <div class="w-96 bg-[#2E324A] border-l border-gray-700 flex flex-col">
            <!-- Cart Header -->
            <div class="p-4 border-b border-gray-700">
                <h2 class="text-lg font-semibold text-white">{{ __('Panier') }}</h2>
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto p-4">
                @forelse($cart as $item)
                <div class="flex items-center justify-between mb-4 bg-[#1B1D29] p-3 rounded-lg">
                    <div>
                        <h4 class="text-white font-medium">{{ $item['name'] }}</h4>
                        <div class="flex items-center gap-4 mt-1">
                            <div class="flex items-center gap-2">
                                <button wire:click="decrementQuantity({{ $item['id'] }})" class="text-gray-400 hover:text-white">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                </button>
                                <span class="text-white">{{ $item['quantity'] }}</span>
                                <button wire:click="incrementQuantity({{ $item['id'] }})" class="text-gray-400 hover:text-white">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </div>
                            <span class="text-gray-400">{{ number_format($item['price'], 2, ',', ' ') }} €</span>
                        </div>
                    </div>
                    <button wire:click="removeFromCart({{ $item['id'] }})" class="text-gray-400 hover:text-white">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                @empty
                <div class="text-center text-gray-400">
                    {{ __('Le panier est vide') }}
                </div>
                @endforelse
            </div>

            <!-- Cart Actions -->
            <div class="p-4 border-t border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-gray-400">{{ __('Total') }}</span>
                    <span class="text-white text-xl font-semibold">{{ number_format($cartTotal, 2, ',', ' ') }} €</span>
                </div>
                <button wire:click="checkout" class="w-full btn-primary justify-center" @if(empty($cart)) disabled @endif>
                    {{ __('Passer à la caisse') }}
                </button>
            </div>
        </div>
    </div>
</div>
