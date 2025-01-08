<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Terminal de vente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-12 gap-6">
                <!-- Left Side - Products -->
                <div class="col-span-12 lg:col-span-8">
                    <!-- Search and Category Filter -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="flex-1">
                                <x-input type="search" wire:model.live="search" placeholder="{{ __('Rechercher des produits...') }}" class="w-full" />
                            </div>
                            <div class="w-full md:w-64">
                                <select wire:model.live="selectedCategory" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">{{ __('Toutes les catégories') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @forelse($products as $product)
                                <button wire:click="addToCart({{ $product->id }})" class="flex flex-col items-center p-4 border rounded-lg hover:bg-gray-50 transition-colors {{ !$product->active ? 'opacity-50' : '' }}">
                                    @if($product->image_path)
                                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-24 h-24 object-cover rounded-lg mb-2">
                                    @else
                                        <div class="w-24 h-24 bg-gray-100 rounded-lg mb-2 flex items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <h3 class="text-sm font-medium text-gray-900 text-center">{{ $product->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $product->formatted_price }}</p>
                                    @if($product->track_stock)
                                        <p class="text-xs text-gray-500">{{ __('Stock') }}: {{ $product->current_stock }}</p>
                                    @endif
                                </button>
                            @empty
                                <div class="col-span-full text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('Aucun produit trouvé') }}</h3>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Right Side - Cart -->
                <div class="col-span-12 lg:col-span-4 space-y-6">
                    <!-- Customer Selection -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <select wire:model.live="selectedCustomer" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">{{ __('Sélectionner un client (Optionnel)') }}</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->full_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Cart Items -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Panier') }}</h3>
                            @if(count($cart) > 0)
                                <div class="space-y-4">
                                    @foreach($cart as $item)
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <h4 class="text-sm font-medium text-gray-900">{{ $item['name'] }}</h4>
                                                <div class="flex items-center mt-1">
                                                    <div class="flex items-center space-x-2">
                                                        <button wire:click="decrementQuantity('{{ $item['id'] }}')" class="text-gray-500 hover:text-gray-700">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                            </svg>
                                                        </button>
                                                        <span class="text-sm text-gray-900">{{ $item['quantity'] }}</span>
                                                        <button wire:click="incrementQuantity('{{ $item['id'] }}')" class="text-gray-500 hover:text-gray-700">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <span class="text-sm text-gray-500 ml-4">x {{ $item['price'] }}</span>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-4">
                                                <span class="text-sm font-medium text-gray-900">{{ $item['total'] }}</span>
                                                <button wire:click="removeFromCart('{{ $item['id'] }}')" class="text-red-500 hover:text-red-700">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="border-t border-gray-200 mt-4 pt-4 space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">{{ __('Sous-total') }}</span>
                                        <span class="text-gray-900">{{ $subtotal }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">{{ __('TVA') }}</span>
                                        <span class="text-gray-900">{{ $tax }}</span>
                                    </div>
                                    <div class="flex justify-between text-base font-medium">
                                        <span class="text-gray-900">{{ __('Total') }}</span>
                                        <span class="text-gray-900">{{ $total }}</span>
                                    </div>
                                </div>

                                <div class="mt-6 space-y-4">
                                    <select wire:model="paymentMethod" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">{{ __('Sélectionner le mode de paiement') }}</option>
                                        <option value="cash">{{ __('Espèces') }}</option>
                                        <option value="card">{{ __('Carte bancaire') }}</option>
                                        <option value="bank_transfer">{{ __('Virement bancaire') }}</option>
                                        <option value="mobile_payment">{{ __('Paiement mobile') }}</option>
                                        <option value="other">{{ __('Autre') }}</option>
                                    </select>

                                    @if($paymentMethod === 'cash')
                                        <div class="space-y-4">
                                            <div>
                                                <x-label for="receivedAmount" value="{{ __('Montant reçu') }}" />
                                                <x-input id="receivedAmount" type="number" step="0.01" class="mt-1 block w-full" wire:model.live="receivedAmount" />
                                            </div>
                                            @if($change > 0)
                                                <div class="text-sm">
                                                    <span class="text-gray-500">{{ __('Monnaie à rendre') }}:</span>
                                                    <span class="text-gray-900 font-medium">{{ $change }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <div>
                                        <x-label for="notes" value="{{ __('Notes (Optionnel)') }}" />
                                        <textarea id="notes" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model="notes" rows="2"></textarea>
                                    </div>

                                    <div class="flex space-x-3">
                                        <x-button wire:click="clearCart" class="flex-1 justify-center bg-gray-600 hover:bg-gray-700">
                                            {{ __('Vider') }}
                                        </x-button>
                                        <x-button wire:click="completeSale" class="flex-1 justify-center">
                                            {{ __('Finaliser la vente') }}
                                        </x-button>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('Le panier est vide') }}</h3>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Receipt Modal -->
    <x-dialog-modal wire:model="showReceiptModal">
        <x-slot name="title">
            {{ __('Reçu de vente') }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div class="text-center">
                    <h3 class="text-lg font-bold">{{ config('app.name') }}</h3>
                    <p class="text-sm text-gray-500">{{ __('Reçu N°') }}: {{ $lastSale?->receipt_number }}</p>
                    <p class="text-sm text-gray-500">{{ $lastSale?->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div class="border-t border-b border-gray-200 py-4">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-gray-500">
                                <th class="text-left">{{ __('Article') }}</th>
                                <th class="text-right">{{ __('Qté') }}</th>
                                <th class="text-right">{{ __('Prix') }}</th>
                                <th class="text-right">{{ __('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($lastSale?->items ?? [] as $item)
                                <tr>
                                    <td class="py-2">{{ $item->product->name }}</td>
                                    <td class="text-right">{{ $item->quantity }}</td>
                                    <td class="text-right">{{ $item->formatted_unit_price }}</td>
                                    <td class="text-right">{{ $item->formatted_total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">{{ __('Sous-total') }}</span>
                        <span>{{ $lastSale?->formatted_subtotal }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">{{ __('TVA') }}</span>
                        <span>{{ $lastSale?->formatted_tax }}</span>
                    </div>
                    <div class="flex justify-between font-bold">
                        <span>{{ __('Total') }}</span>
                        <span>{{ $lastSale?->formatted_total }}</span>
                    </div>
                    @if($lastSale?->payment_method === 'cash')
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">{{ __('Montant reçu') }}</span>
                            <span>{{ $lastSale?->formatted_received_amount }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">{{ __('Monnaie rendue') }}</span>
                            <span>{{ $lastSale?->formatted_change_amount }}</span>
                        </div>
                    @endif
                </div>

                <div class="text-center text-sm text-gray-500">
                    <p>{{ __('Mode de paiement') }}: {{ __($lastSale?->payment_method) }}</p>
                    @if($lastSale?->customer)
                        <p>{{ __('Client') }}: {{ $lastSale?->customer->full_name }}</p>
                    @endif
                    @if($lastSale?->notes)
                        <p>{{ __('Notes') }}: {{ $lastSale?->notes }}</p>
                    @endif
                </div>

                <div class="text-center text-xs text-gray-500">
                    <p>{{ __('Merci de votre visite !') }}</p>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-end space-x-3">
                <x-secondary-button wire:click="$set('showReceiptModal', false)">
                    {{ __('Fermer') }}
                </x-secondary-button>
                <x-button wire:click="printReceipt">
                    {{ __('Imprimer') }}
                </x-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div> 