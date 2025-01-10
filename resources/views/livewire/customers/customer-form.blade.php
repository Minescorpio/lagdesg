<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form wire:submit="save" class="space-y-8">
            <div class="bg-[#1F2337] shadow-sm rounded-lg">
                <!-- Header -->
                <div class="px-4 py-5 sm:px-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-white">
                            {{ $customerId ? __('Modifier le client') : __('Nouveau client') }}
                        </h3>
                        <div class="flex items-center space-x-3">
                            <button type="button" class="btn-secondary">
                                {{ __('Annuler') }}
                            </button>
                            <button type="submit" class="btn-primary">
                                {{ __('Créer') }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="px-4 py-5 sm:p-6">
                    <!-- Personal Information -->
                    <div class="mb-8">
                        <h4 class="text-lg font-medium text-white mb-4">{{ __('Informations personnelles') }}</h4>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <!-- First Name -->
                            <div>
                                <label for="first_name" class="form-label required">{{ __('Prénom') }}</label>
                                <input type="text" wire:model="first_name" id="first_name" class="form-input" required>
                                @error('first_name') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label for="last_name" class="form-label required">{{ __('Nom') }}</label>
                                <input type="text" wire:model="last_name" id="last_name" class="form-input" required>
                                @error('last_name') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input type="email" wire:model="email" id="email" class="form-input">
                                @error('email') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="form-label">{{ __('Téléphone') }}</label>
                                <input type="tel" wire:model="phone" id="phone" class="form-input">
                                @error('phone') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Birth Date -->
                            <div>
                                <label for="birth_date" class="form-label">{{ __('Date de naissance') }}</label>
                                <input type="date" wire:model="birth_date" id="birth_date" class="form-input">
                                @error('birth_date') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Customer Code -->
                            <div>
                                <label for="customer_code" class="form-label">{{ __('Code client') }}</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" wire:model="customer_code" id="customer_code" class="form-input flex-1 rounded-r-none">
                                    <button type="button" wire:click="generateCustomerCode"
                                        class="relative -ml-px inline-flex items-center px-4 py-2 border border-gray-600 text-sm font-medium rounded-r-md text-white bg-[#2E324A] hover:bg-[#373B56] focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                        {{ __('Générer') }}
                                    </button>
                                </div>
                                @error('customer_code') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mb-8">
                        <h4 class="text-lg font-medium text-white mb-4">{{ __('Adresse') }}</h4>
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Street Address -->
                            <div>
                                <label for="address" class="form-label">{{ __('Adresse') }}</label>
                                <input type="text" wire:model="address" id="address" class="form-input">
                                @error('address') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <!-- City -->
                                <div>
                                    <label for="city" class="form-label">{{ __('Ville') }}</label>
                                    <input type="text" wire:model="city" id="city" class="form-input">
                                    @error('city') <span class="form-error">{{ $message }}</span> @enderror
                                </div>

                                <!-- Postal Code -->
                                <div>
                                    <label for="postal_code" class="form-label">{{ __('Code postal') }}</label>
                                    <input type="text" wire:model="postal_code" id="postal_code" class="form-input">
                                    @error('postal_code') <span class="form-error">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Country -->
                            <div>
                                <label for="country" class="form-label">{{ __('Pays') }}</label>
                                <select wire:model="country" id="country" class="form-select">
                                    <option value="">{{ __('Sélectionner un pays') }}</option>
                                    @foreach($countries as $code => $name)
                                        <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('country') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Loyalty & Credit -->
                    <div class="mb-8">
                        <h4 class="text-lg font-medium text-white mb-4">{{ __('Fidélité & Crédit') }}</h4>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Loyalty Points -->
                            <div>
                                <label for="loyalty_points" class="form-label">{{ __('Points de fidélité') }}</label>
                                <input type="number" wire:model="loyalty_points" id="loyalty_points" class="form-input" min="0" step="1">
                                @error('loyalty_points') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Credit Balance -->
                            <div>
                                <label for="credit_balance" class="form-label">{{ __('Solde crédit') }}</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-400 sm:text-sm">€</span>
                                    </div>
                                    <input type="number" wire:model="credit_balance" id="credit_balance" class="form-input pl-7" step="0.01" min="0">
                                    @error('credit_balance') <span class="form-error">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div>
                        <h4 class="text-lg font-medium text-white mb-4">{{ __('Informations complémentaires') }}</h4>
                        <div>
                            <label for="notes" class="form-label">{{ __('Notes') }}</label>
                            <textarea wire:model="notes" id="notes" rows="3" class="form-textarea"></textarea>
                            @error('notes') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    .form-label {
        @apply block text-sm font-medium text-gray-200;
    }
    .form-label.required::after {
        content: "*";
        @apply text-red-500 ml-1;
    }
    .form-input {
        @apply mt-1 block w-full rounded-md border-gray-600 bg-white text-black shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
    }
    .form-select {
        @apply mt-1 block w-full rounded-md border-gray-600 bg-white text-black shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
    }
    .form-textarea {
        @apply mt-1 block w-full rounded-md border-gray-600 bg-white text-black shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
    }
    .form-error {
        @apply mt-1 text-sm text-red-600;
    }
    .btn-primary {
        @apply inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500;
    }
    .btn-secondary {
        @apply inline-flex items-center px-4 py-2 border border-gray-700 rounded-md shadow-sm text-sm font-medium text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500;
    }
</style>
@endpush 