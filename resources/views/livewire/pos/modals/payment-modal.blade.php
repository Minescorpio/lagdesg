<div>
    <x-dialog-modal wire:model.live="showPaymentModal">
        <x-slot name="title">
            {{ __('Procéder au paiement') }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-6">
                <!-- Order Summary -->
                <div class="bg-[#1B1D29] rounded-lg p-4">
                    <h4 class="text-lg font-semibold text-white mb-4">{{ __('Résumé de la commande') }}</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between text-gray-400">
                            <span>{{ __('Sous-total') }}</span>
                            <span>{{ number_format($subtotal, 2, ',', ' ') }} €</span>
                        </div>
                        <div class="flex justify-between text-gray-400">
                            <span>{{ __('TVA') }} ({{ $taxRate }}%)</span>
                            <span>{{ number_format($tax, 2, ',', ' ') }} €</span>
                        </div>
                        @if($discount > 0)
                            <div class="flex justify-between text-gray-400">
                                <span>{{ __('Remise') }}</span>
                                <span>-{{ number_format($discount, 2, ',', ' ') }} €</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-white text-lg font-semibold pt-2 border-t border-gray-700">
                            <span>{{ __('Total') }}</span>
                            <span>{{ number_format($total, 2, ',', ' ') }} €</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">
                        {{ __('Mode de paiement') }}
                    </label>
                    <div class="grid grid-cols-3 gap-4">
                        <button 
                            wire:click="$set('paymentMethod', 'cash')"
                            class="payment-method-btn {{ $paymentMethod === 'cash' ? 'active' : '' }}"
                        >
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            {{ __('Espèces') }}
                        </button>
                        <button 
                            wire:click="$set('paymentMethod', 'card')"
                            class="payment-method-btn {{ $paymentMethod === 'card' ? 'active' : '' }}"
                        >
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            {{ __('Carte') }}
                        </button>
                        <button 
                            wire:click="$set('paymentMethod', 'transfer')"
                            class="payment-method-btn {{ $paymentMethod === 'transfer' ? 'active' : '' }}"
                        >
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                            {{ __('Virement') }}
                        </button>
                    </div>
                </div>

                <!-- Cash Payment Details -->
                @if($paymentMethod === 'cash')
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">
                            {{ __('Montant reçu') }}
                        </label>
                        <x-input 
                            type="number" 
                            step="0.01" 
                            class="w-full"
                            wire:model.live="receivedAmount"
                        />
                        @if($change > 0)
                            <p class="mt-2 text-sm text-gray-400">
                                {{ __('Monnaie à rendre') }}: <span class="font-semibold text-white">{{ number_format($change, 2, ',', ' ') }} €</span>
                            </p>
                        @endif
                    </div>
                @endif

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">
                        {{ __('Notes') }}
                    </label>
                    <x-textarea 
                        class="w-full"
                        rows="3"
                        wire:model="notes"
                        placeholder="{{ __('Ajouter des notes à la vente...') }}"
                    ></x-textarea>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-between gap-4">
                <x-button wire:click="$set('showPaymentModal', false)">
                    {{ __('Annuler') }}
                </x-button>
                <x-button 
                    wire:click="processPayment"
                    class="btn-success"
                    :disabled="!$paymentMethod || ($paymentMethod === 'cash' && $receivedAmount < $total)"
                >
                    {{ __('Valider le paiement') }}
                </x-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div> 