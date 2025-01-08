<div>
    <x-dialog-modal wire:model.live="showDiscountModal">
        <x-slot name="title">
            {{ __('Appliquer une remise') }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-6">
                <!-- Current Total -->
                <div class="text-center">
                    <p class="text-gray-400">{{ __('Total actuel') }}</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($total, 2, ',', ' ') }} €</p>
                </div>

                <!-- Discount Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">
                        {{ __('Type de remise') }}
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <button 
                            wire:click="$set('discountType', 'fixed')"
                            class="discount-type-btn {{ $discountType === 'fixed' ? 'active' : '' }}"
                        >
                            {{ __('Montant fixe') }}
                        </button>
                        <button 
                            wire:click="$set('discountType', 'percentage')"
                            class="discount-type-btn {{ $discountType === 'percentage' ? 'active' : '' }}"
                        >
                            {{ __('Pourcentage') }}
                        </button>
                    </div>
                </div>

                <!-- Discount Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">
                        {{ $discountType === 'percentage' ? __('Pourcentage de remise') : __('Montant de la remise') }}
                    </label>
                    <div class="relative">
                        <x-input 
                            type="number" 
                            step="{{ $discountType === 'percentage' ? '1' : '0.01' }}"
                            min="0"
                            max="{{ $discountType === 'percentage' ? '100' : '' }}"
                            class="w-full pr-8"
                            wire:model.live="discountAmount"
                        />
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                            {{ $discountType === 'percentage' ? '%' : '€' }}
                        </span>
                    </div>
                </div>

                <!-- Final Amount -->
                <div class="text-center">
                    <p class="text-gray-400">{{ __('Montant après remise') }}</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($finalAmount, 2, ',', ' ') }} €</p>
                    <p class="text-sm text-gray-400">
                        {{ __('Économie') }}: {{ number_format($savings, 2, ',', ' ') }} €
                    </p>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-between gap-4">
                <x-button wire:click="$set('showDiscountModal', false)">
                    {{ __('Annuler') }}
                </x-button>
                <x-button 
                    wire:click="applyDiscount"
                    class="btn-primary"
                    :disabled="!$discountAmount || $discountAmount <= 0"
                >
                    {{ __('Appliquer la remise') }}
                </x-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div> 