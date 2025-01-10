<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Terminal de vente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-12 gap-6">
                <!-- Left Side - Products -->
                <div class="col-span-12 lg:col-span-8">
                    <!-- Search and Category Filter -->
                    <div class="card mb-6">
                        <div class="card-body">
                            <div class="flex flex-col md:flex-row gap-4">
                                <div class="flex-1">
                                    <div class="relative">
                                        <input type="search" wire:model.live="search" placeholder="{{ __('Rechercher des produits...') }}" class="search-input" />
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full md:w-64">
                                    <select wire:model.live="selectedCategory" class="form-select">
                                        <option value="">{{ __('Toutes les catégories') }}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="card">
                        <div class="card-body">
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @forelse($products as $product)
                                    <button wire:click="addToCart({{ $product->id }})" 
                                        class="card hover:border-[#FFD700] transition-all duration-200 cursor-pointer {{ !$product->active ? 'opacity-50' : '' }}">
                                        <div class="card-body">
                                            @if($product->image_path)
                                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-32 object-cover rounded-lg mb-4">
                                            @else
                                                <div class="w-full h-32 bg-[#2E324A] rounded-lg mb-4 flex items-center justify-center">
                                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <h3 class="text-sm font-medium text-white text-center">{{ $product->name }}</h3>
                                            <p class="text-[#FFD700] font-bold text-center">{{ $product->formatted_price }}</p>
                                            @if($product->track_stock)
                                                <p class="text-xs text-gray-400 text-center">{{ __('Stock') }}: {{ $product->current_stock }}</p>
                                            @endif
                                        </div>
                                    </button>
                                @empty
                                    <div class="col-span-full">
                                        <div class="text-center py-12">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-400">{{ __('Aucun produit trouvé') }}</h3>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Cart -->
                <div class="col-span-12 lg:col-span-4 space-y-6">
                    <!-- Customer Selection -->
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label">{{ __('Client') }}</label>
                                <select wire:model.live="selectedCustomer" class="form-select">
                                    <option value="">{{ __('Sélectionner un client (Optionnel)') }}</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('customers.create') }}" class="btn-primary w-full flex items-center justify-center">
                                    <i class="fas fa-user-plus mr-2"></i>
                                    {{ __('NOUVEAU CLIENT') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Cart -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-white">{{ __('Panier') }}</h3>
                        </div>
                        <div class="card-body">
                            @if(count($cart) > 0)
                                <div class="space-y-4">
                                    @foreach($cart as $item)
                                        <div class="flex items-center justify-between bg-[#2E324A] p-3 rounded-lg">
                                            <div class="flex-1">
                                                <h4 class="font-medium text-white">{{ $item['name'] }}</h4>
                                                <div class="flex items-center mt-1">
                                                    <div class="flex items-center space-x-2">
                                                        <button wire:click="decrementQuantity('{{ $item['id'] }}')" class="btn-secondary btn-sm">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                            </svg>
                                                        </button>
                                                        <span class="text-white">{{ $item['quantity'] }}</span>
                                                        <button wire:click="incrementQuantity('{{ $item['id'] }}')" class="btn-secondary btn-sm">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <span class="text-[#FFD700] ml-4">{{ $item['formatted_price'] }}</span>
                                                </div>
                                            </div>
                                            <button wire:click="removeFromCart('{{ $item['id'] }}')" class="btn-danger btn-sm ml-4">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="border-t border-gray-700 mt-6 pt-4">
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="text-gray-400">{{ __('Sous-total') }}</span>
                                        <span class="text-white">{{ $subtotal }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="text-gray-400">{{ __('TVA') }}</span>
                                        <span class="text-white">{{ $tax }}</span>
                                    </div>
                                    <div class="flex justify-between text-lg font-semibold mt-4">
                                        <span class="text-white">{{ __('Total') }}</span>
                                        <span class="text-[#FFD700]">{{ $total }}</span>
                                    </div>

                                    <div class="mt-6 space-y-4">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Mode de paiement') }}</label>
                                            <select wire:model="paymentMethod" class="form-select">
                                                <option value="">{{ __('Sélectionner le mode de paiement') }}</option>
                                                <option value="cash">{{ __('Espèces') }}</option>
                                                <option value="card">{{ __('Carte bancaire') }}</option>
                                                <option value="bank_transfer">{{ __('Virement bancaire') }}</option>
                                                <option value="mobile_payment">{{ __('Paiement mobile') }}</option>
                                                <option value="other">{{ __('Autre') }}</option>
                                            </select>
                                        </div>

                                        @if($paymentMethod === 'cash')
                                            <div class="space-y-4">
                                                <div class="form-group">
                                                    <label class="form-label">{{ __('Montant reçu') }}</label>
                                                    <input type="number" step="0.01" class="form-input" wire:model.live="receivedAmount" />
                                                </div>
                                                @if($change > 0)
                                                    <div class="alert alert-success">
                                                        <span class="text-gray-400">{{ __('Monnaie à rendre') }}:</span>
                                                        <span class="text-[#FFD700] font-medium">{{ $change }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <label class="form-label">{{ __('Notes (Optionnel)') }}</label>
                                            <textarea class="form-input" wire:model="notes" rows="2"></textarea>
                                        </div>

                                        <div class="flex space-x-3">
                                            <button wire:click="clearCart" class="btn-secondary flex-1">
                                                {{ __('Vider') }}
                                            </button>
                                            <button wire:click="completeSale" class="btn-primary flex-1">
                                                {{ __('Finaliser la vente') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-400">{{ __('Le panier est vide') }}</h3>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Receipt Modal -->
    <div x-data="{ open: @entangle('showReceiptModal') }"
         x-show="open"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-lg bg-[#1F2937] px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="absolute right-0 top-0 pr-4 pt-4">
                    <button type="button" 
                            @click="open = false"
                            class="rounded-md bg-[#1F2937] text-gray-400 hover:text-gray-300">
                        <span class="sr-only">{{ __('Fermer') }}</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="space-y-4">
                        <div class="text-center">
                            <h3 class="text-lg font-bold text-white">{{ config('app.name') }}</h3>
                            <p class="text-sm text-gray-400">{{ __('Reçu N°') }}: {{ $lastSale?->receipt_number }}</p>
                            <p class="text-sm text-gray-400">{{ $lastSale?->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div class="border-t border-b border-gray-700 py-4">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr>
                                        <th class="text-left text-gray-400">{{ __('Article') }}</th>
                                        <th class="text-right text-gray-400">{{ __('Qté') }}</th>
                                        <th class="text-right text-gray-400">{{ __('Prix') }}</th>
                                        <th class="text-right text-gray-400">{{ __('Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700">
                                    @foreach($lastSale?->items ?? [] as $item)
                                        <tr>
                                            <td class="py-2 text-white">{{ $item->product->name }}</td>
                                            <td class="text-right text-white">{{ $item->quantity }}</td>
                                            <td class="text-right text-white">{{ $item->formatted_unit_price }}</td>
                                            <td class="text-right text-[#FFD700]">{{ $item->formatted_total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">{{ __('Sous-total') }}</span>
                                <span class="text-white">{{ $lastSale?->formatted_subtotal }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">{{ __('TVA') }}</span>
                                <span class="text-white">{{ $lastSale?->formatted_tax }}</span>
                            </div>
                            <div class="flex justify-between font-bold">
                                <span class="text-white">{{ __('Total') }}</span>
                                <span class="text-[#FFD700]">{{ $lastSale?->formatted_total }}</span>
                            </div>
                            @if($lastSale?->payment_method === 'cash')
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-400">{{ __('Montant reçu') }}</span>
                                    <span class="text-white">{{ $lastSale?->formatted_received_amount }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-400">{{ __('Monnaie rendue') }}</span>
                                    <span class="text-[#FFD700]">{{ $lastSale?->formatted_change_amount }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="text-center text-sm text-gray-400">
                            <p>{{ __('Mode de paiement') }}: {{ __($lastSale?->payment_method) }}</p>
                            @if($lastSale?->customer)
                                <p>{{ __('Client') }}: {{ $lastSale?->customer->full_name }}</p>
                            @endif
                            @if($lastSale?->notes)
                                <p>{{ __('Notes') }}: {{ $lastSale?->notes }}</p>
                            @endif
                        </div>

                        <div class="text-center text-xs text-gray-400">
                            <p>{{ __('Merci de votre visite !') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                    <button type="button"
                            wire:click="printReceipt"
                            class="btn-primary w-full">
                        {{ __('Imprimer') }}
                    </button>
                    <button type="button"
                            @click="open = false"
                            class="btn-secondary w-full">
                        {{ __('Fermer') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> 