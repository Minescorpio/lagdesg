<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Nouveau client') }}
            </h2>
            <a href="{{ route('customers.index') }}" class="btn-secondary">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                </svg>
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#1F2337] rounded-lg shadow-sm">
                <div class="p-6">
                    <form wire:submit="save" class="space-y-8">
                        <!-- Personal Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-6">{{ __('Informations personnelles') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="title" class="block text-sm font-medium text-white mb-2">{{ __('Civilité') }}</label>
                                    <select id="title" wire:model="title" class="form-select w-full text-white">
                                        <option value="">{{ __('Sélectionner une civilité') }}</option>
                                        <option value="mr">{{ __('M.') }}</option>
                                        <option value="mrs">{{ __('Mme') }}</option>
                                        <option value="ms">{{ __('Mlle') }}</option>
                                    </select>
                                    @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-white mb-2">{{ __('Prénom') }} <span class="text-red-500">*</span></label>
                                    <input type="text" id="first_name" wire:model="first_name" class="form-input w-full text-white" required>
                                    @error('first_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-white mb-2">{{ __('Nom') }} <span class="text-red-500">*</span></label>
                                    <input type="text" id="last_name" wire:model="last_name" class="form-input w-full text-white" required>
                                    @error('last_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="birth_date" class="block text-sm font-medium text-white mb-2">{{ __('Date de naissance') }}</label>
                                    <input type="date" id="birth_date" wire:model="birth_date" class="form-input w-full text-white">
                                    @error('birth_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="company" class="block text-sm font-medium text-white mb-2">{{ __('Société') }}</label>
                                    <input type="text" id="company" wire:model="company" class="form-input w-full text-white">
                                    @error('company') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-6">{{ __('Coordonnées') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="email" class="block text-sm font-medium text-white mb-2">{{ __('Email') }}</label>
                                    <input type="email" id="email" wire:model="email" class="form-input w-full text-white">
                                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-white mb-2">{{ __('Téléphone') }}</label>
                                    <input type="tel" id="phone" wire:model="phone" class="form-input w-full text-white">
                                    @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-span-2">
                                    <label for="address" class="block text-sm font-medium text-white mb-2">{{ __('Adresse') }}</label>
                                    <textarea id="address" wire:model="address" class="form-textarea w-full text-white" rows="2"></textarea>
                                    @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="postal_code" class="block text-sm font-medium text-white mb-2">{{ __('Code postal') }}</label>
                                    <input type="text" id="postal_code" wire:model="postal_code" class="form-input w-full text-white">
                                    @error('postal_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="city" class="block text-sm font-medium text-white mb-2">{{ __('Ville') }}</label>
                                    <input type="text" id="city" wire:model="city" class="form-input w-full text-white">
                                    @error('city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-6">{{ __('Informations complémentaires') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-2">
                                    <label for="notes" class="block text-sm font-medium text-white mb-2">{{ __('Notes') }}</label>
                                    <textarea id="notes" wire:model="notes" class="form-textarea w-full text-white" rows="3"></textarea>
                                    @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" id="active" wire:model="active" class="form-checkbox">
                                    <label for="active" class="text-sm text-white">{{ __('Actif') }}</label>
                                </div>

                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" id="accepts_marketing" wire:model="accepts_marketing" class="form-checkbox">
                                    <label for="accepts_marketing" class="text-sm text-white">{{ __('Accepte les communications marketing') }}</label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('customers.index') }}" class="btn-secondary">
                                {{ __('Annuler') }}
                            </a>
                            <button type="submit" class="btn-primary">
                                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ __('Créer le client') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 