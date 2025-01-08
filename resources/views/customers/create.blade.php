<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add Customer') }}
            </h2>
            <a href="{{ route('customers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form wire:submit.prevent="save" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Informations personnelles -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Personal Information') }}</h3>
                            
                            <!-- Civilité -->
                            <div>
                                <x-label for="title" value="{{ __('Title') }}" />
                                <select id="title" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model="title">
                                    <option value="">{{ __('Select Title') }}</option>
                                    <option value="mr">{{ __('Mr.') }}</option>
                                    <option value="mrs">{{ __('Mrs.') }}</option>
                                    <option value="ms">{{ __('Ms.') }}</option>
                                </select>
                                <x-input-error for="title" class="mt-2" />
                            </div>

                            <!-- Prénom -->
                            <div>
                                <x-label for="first_name" value="{{ __('First Name') }}" />
                                <x-input id="first_name" type="text" class="mt-1 block w-full" wire:model="first_name" required />
                                <x-input-error for="first_name" class="mt-2" />
                            </div>

                            <!-- Nom -->
                            <div>
                                <x-label for="last_name" value="{{ __('Last Name') }}" />
                                <x-input id="last_name" type="text" class="mt-1 block w-full" wire:model="last_name" required />
                                <x-input-error for="last_name" class="mt-2" />
                            </div>

                            <!-- Date de naissance -->
                            <div>
                                <x-label for="birth_date" value="{{ __('Birth Date') }}" />
                                <x-input id="birth_date" type="date" class="mt-1 block w-full" wire:model="birth_date" />
                                <x-input-error for="birth_date" class="mt-2" />
                            </div>
                        </div>

                        <!-- Coordonnées -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Contact Information') }}</h3>
                            
                            <!-- Email -->
                            <div>
                                <x-label for="email" value="{{ __('Email') }}" />
                                <x-input id="email" type="email" class="mt-1 block w-full" wire:model="email" />
                                <x-input-error for="email" class="mt-2" />
                            </div>

                            <!-- Téléphone -->
                            <div>
                                <x-label for="phone" value="{{ __('Phone') }}" />
                                <x-input id="phone" type="tel" class="mt-1 block w-full" wire:model="phone" />
                                <x-input-error for="phone" class="mt-2" />
                            </div>

                            <!-- Adresse -->
                            <div>
                                <x-label for="address" value="{{ __('Address') }}" />
                                <textarea id="address" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model="address" rows="2"></textarea>
                                <x-input-error for="address" class="mt-2" />
                            </div>

                            <!-- Code postal -->
                            <div>
                                <x-label for="postal_code" value="{{ __('Postal Code') }}" />
                                <x-input id="postal_code" type="text" class="mt-1 block w-full" wire:model="postal_code" />
                                <x-input-error for="postal_code" class="mt-2" />
                            </div>

                            <!-- Ville -->
                            <div>
                                <x-label for="city" value="{{ __('City') }}" />
                                <x-input id="city" type="text" class="mt-1 block w-full" wire:model="city" />
                                <x-input-error for="city" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Options supplémentaires -->
                    <div class="mt-6 space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Additional Options') }}</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Programme de fidélité -->
                            <div>
                                <x-label for="loyalty_program" value="{{ __('Loyalty Program') }}" />
                                <select id="loyalty_program" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model="loyalty_program">
                                    <option value="">{{ __('Select Program') }}</option>
                                    @foreach($loyaltyPrograms as $program)
                                        <option value="{{ $program->id }}">{{ $program->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error for="loyalty_program" class="mt-2" />
                            </div>

                            <!-- Notes -->
                            <div>
                                <x-label for="notes" value="{{ __('Notes') }}" />
                                <textarea id="notes" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model="notes" rows="3"></textarea>
                                <x-input-error for="notes" class="mt-2" />
                            </div>
                        </div>

                        <!-- Options -->
                        <div class="flex space-x-6">
                            <label class="flex items-center">
                                <x-checkbox wire:model="active" />
                                <span class="ml-2 text-sm text-gray-600">{{ __('Active') }}</span>
                            </label>

                            <label class="flex items-center">
                                <x-checkbox wire:model="accepts_marketing" />
                                <span class="ml-2 text-sm text-gray-600">{{ __('Accepts Marketing') }}</span>
                            </label>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="mt-6 flex justify-end space-x-3">
                        <x-secondary-button type="button" wire:click="cancel">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-button type="submit" wire:loading.attr="disabled">
                            {{ __('Create Customer') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 