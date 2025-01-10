<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Terminal de vente') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Cart -->
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

                <!-- Cart Items Count -->
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

            <!-- Quick Actions -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-white mb-4">{{ __('Actions rapides') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Clear Cart -->
                    <button wire:click="clearCart" class="btn-primary flex items-center justify-center gap-2 py-3 px-4 text-base">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        {{ __('Vider le panier') }}
                    </button>

                    <!-- Select Customer -->
                    <button wire:click="openCustomerModal" class="btn-primary flex items-center justify-center gap-2 py-3 px-4 text-base">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        {{ __('Sélectionner un client') }}
                    </button>

                    <!-- Apply Discount -->
                    <button wire:click="openDiscountModal" class="btn-primary flex items-center justify-center gap-2 py-3 px-4 text-base">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                        </svg>
                        {{ __('Appliquer une remise') }}
                    </button>
                    @error('cart')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror

                    <!-- Process Payment -->
                    <button wire:click="openPaymentModal" class="btn-primary flex items-center justify-center gap-2 py-3 px-4 text-base">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        {{ __('Procéder au paiement') }}
                    </button>
                </div>
            </div>

            <!-- Cart Items -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-white">{{ __('Articles du panier') }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="table-header px-6 py-3 text-white">{{ __('Produit') }}</th>
                                <th class="table-header px-6 py-3 text-white">{{ __('Prix unitaire') }}</th>
                                <th class="table-header px-6 py-3 text-white">{{ __('Quantité') }}</th>
                                <th class="table-header px-6 py-3 text-white">{{ __('Total') }}</th>
                                <th class="table-header px-6 py-3 text-white">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @forelse($cartItems as $item)
                                <tr class="hover:bg-[#2E324A] transition-colors duration-150">
                                    <td class="px-6 py-4">{{ $item->name }}</td>
                                    <td class="px-6 py-4">{{ number_format($item->price, 2, ',', ' ') }} €</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <button wire:click="decrementQuantity('{{ $item->id }}')" class="btn-icon">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                </svg>
                                            </button>
                                            <span>{{ $item->quantity }}</span>
                                            <button wire:click="incrementQuantity('{{ $item->id }}')" class="btn-icon">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">{{ number_format($item->price * $item->quantity, 2, ',', ' ') }} €</td>
                                    <td class="px-6 py-4">
                                        <button wire:click="removeItem('{{ $item->id }}')" class="btn-icon text-red-500 hover:text-red-600">
                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-400">
                                        {{ __('Le panier est vide') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mb-6">
                <div class="card-body">
                    <!-- Barcode Scanner Input -->
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" 
                                wire:model.live="barcodeInput"
                                wire:keydown.enter="handleBarcodeScan"
                                id="barcode-input"
                                placeholder="{{ __('Scanner un code-barres...') }}"
                                class="search-input pl-16"
                                autocomplete="off"
                                x-data
                                x-init="$el.focus()"
                                @click="$el.select()"
                                @focusout="setTimeout(() => $el.focus(), 100)">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row gap-4">
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    @include('livewire.pos.modals.customer-modal')
    @include('livewire.pos.modals.payment-modal')
    @include('livewire.pos.modals.discount-modal')
</div>
