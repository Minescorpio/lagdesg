<div class="bg-[#1B1D29]">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-white">
                {{ __('Create New Supplier') }}
            </h2>
            <a href="{{ route('fournisseurs.index') }}" class="px-4 py-2 text-sm font-medium text-white transition duration-150 ease-in-out bg-gray-600 border border-transparent rounded-md hover:bg-gray-500 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-700">
                {{ __('Back to Suppliers') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-[#2E324A] shadow-xl sm:rounded-lg">
                <div class="p-6 bg-[#2E324A]">
                    <form wire:submit="save" class="space-y-6">
                        <div>
                            <x-label for="nom" value="{{ __('Name') }}" class="text-white" />
                            <x-input id="nom" type="text" class="block w-full mt-1 text-white bg-[#1B1D29] border-gray-700" wire:model="nom" required />
                            <x-input-error for="nom" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="email" value="{{ __('Email') }}" class="text-white" />
                            <x-input id="email" type="email" class="block w-full mt-1 text-white bg-[#1B1D29] border-gray-700" wire:model="email" />
                            <x-input-error for="email" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="telephone" value="{{ __('Phone') }}" class="text-white" />
                            <x-input id="telephone" type="text" class="block w-full mt-1 text-white bg-[#1B1D29] border-gray-700" wire:model="telephone" />
                            <x-input-error for="telephone" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="adresse" value="{{ __('Address') }}" class="text-white" />
                            <x-textarea id="adresse" class="block w-full mt-1 text-white bg-[#1B1D29] border-gray-700" wire:model="adresse" />
                            <x-input-error for="adresse" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="notes" value="{{ __('Notes') }}" class="text-white" />
                            <x-textarea id="notes" class="block w-full mt-1 text-white bg-[#1B1D29] border-gray-700" wire:model="notes" />
                            <x-input-error for="notes" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-button class="ml-4" wire:loading.attr="disabled">
                                {{ __('Create Supplier') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 