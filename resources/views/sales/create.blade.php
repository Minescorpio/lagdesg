<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight hover:text-[#FFD700] transition-colors duration-200">
                {{ __('Nouvelle vente') }}
            </h2>
            <a href="{{ route('sales.index') }}" class="btn-secondary hover:text-[#FFD700] transition-colors duration-200">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                </svg>
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-body">
                    <form wire:submit="save" class="space-y-8">
                        <!-- Customer Selection -->
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4">{{ __('Client') }}</h3>
                            <div class="form-group">
                                <select id="customer_id" wire:model="customer_id" class="form-select">
                                    <option value="">{{ __('Sélectionner un client (Optionnel)') }}</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->full_name }}</option>
                                    @endforeach
                                </select>
                                @error('customer_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Products List -->
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4">{{ __('Produits') }}</h3>
                            <div class="overflow-x-auto">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Produit') }}</th>
                                            <th>{{ __('Quantité') }}</th>
                                            <th>{{ __('Prix unitaire') }}</th>
                                            <th>{{ __('Total') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $index => $item)
                                            <tr>
                                                <td>
                                                    <select wire:model="items.{{ $index }}.product_id" class="form-select">
                                                        <option value="">{{ __('Sélectionner un produit') }}</option>
                                                        @foreach($products as $product)
                                                            <option value="{{ $product->id }}">{{ $product->name }} - {{ $product->formatted_price }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" wire:model="items.{{ $index }}.quantity" class="form-input" min="1">
                                                </td>
                                                <td class="text-right">{{ isset($items[$index]['unit_price']) ? '€' . number_format($items[$index]['unit_price'], 2) : '-' }}</td>
                                                <td class="text-right">{{ isset($items[$index]['total']) ? '€' . number_format($items[$index]['total'], 2) : '-' }}</td>
                                                <td>
                                                    <button type="button" wire:click="removeItem({{ $index }})" class="btn-danger btn-sm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="mt-4">
                                    <button type="button" wire:click="addItem" class="btn-secondary">
                                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        {{ __('Ajouter un produit') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4">{{ __('Paiement') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="form-group">
                                    <label for="payment_method" class="form-label">{{ __('Moyen de paiement') }}</label>
                                    <select id="payment_method" wire:model="payment_method" class="form-select">
                                        <option value="">{{ __('Sélectionner un moyen de paiement') }}</option>
                                        <option value="cash">{{ __('Espèces') }}</option>
                                        <option value="card">{{ __('Carte bancaire') }}</option>
                                        <option value="transfer">{{ __('Virement') }}</option>
                                    </select>
                                    @error('payment_method') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                @if($payment_method === 'cash')
                                    <div class="form-group">
                                        <label for="payment_amount" class="form-label">{{ __('Montant reçu') }}</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">€</span>
                                            <input type="number" id="payment_amount" wire:model="payment_amount" class="form-input pl-8" step="0.01">
                                        </div>
                                        @error('payment_amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">{{ __('Monnaie à rendre') }}</label>
                                        <div class="text-2xl font-bold text-[#FFD700]">{{ $change }}</div>
                                    </div>
                                @endif

                                <div class="form-group md:col-span-2">
                                    <label class="form-label">{{ __('Total') }}</label>
                                    <div class="text-3xl font-bold text-[#FFD700]">{{ $total }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4">{{ __('Notes') }}</h3>
                            <div class="form-group">
                                <textarea id="notes" wire:model="notes" rows="3" class="form-input"></textarea>
                                @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-3">
                            <button type="button" wire:click="cancel" class="btn-secondary">
                                {{ __('Annuler') }}
                            </button>
                            <button type="submit" class="btn-primary">
                                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ __('Finaliser la vente') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 