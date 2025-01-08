<div>
    <x-dialog-modal wire:model.live="showCustomerModal">
        <x-slot name="title">
            {{ __('Sélectionner un client') }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <!-- Search Input -->
                <div>
                    <x-input 
                        type="text" 
                        class="w-full"
                        placeholder="{{ __('Rechercher un client...') }}"
                        wire:model.live="customerSearch"
                    />
                </div>

                <!-- Customers List -->
                <div class="mt-4 max-h-96 overflow-y-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left">{{ __('Nom') }}</th>
                                <th class="px-4 py-2 text-left">{{ __('Email') }}</th>
                                <th class="px-4 py-2 text-left">{{ __('Téléphone') }}</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @forelse($customers as $customer)
                                <tr class="hover:bg-[#2E324A] transition-colors duration-150">
                                    <td class="px-4 py-2">{{ $customer->name }}</td>
                                    <td class="px-4 py-2">{{ $customer->email }}</td>
                                    <td class="px-4 py-2">{{ $customer->phone }}</td>
                                    <td class="px-4 py-2 text-right">
                                        <x-button wire:click="selectCustomer({{ $customer->id }})">
                                            {{ __('Sélectionner') }}
                                        </x-button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-2 text-center text-gray-400">
                                        {{ __('Aucun client trouvé') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-between gap-4">
                <x-button wire:click="$set('showCustomerModal', false)">
                    {{ __('Annuler') }}
                </x-button>
                <x-button wire:click="$emit('openCustomerForm')" class="btn-primary">
                    {{ __('Nouveau client') }}
                </x-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div> 