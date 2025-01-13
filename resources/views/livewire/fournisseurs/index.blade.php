@php
    $columns = [
        ['key' => 'nom', 'label' => 'Name'],
        ['key' => 'email', 'label' => 'Email'],
        ['key' => 'telephone', 'label' => 'Phone'],
        ['key' => 'adresse', 'label' => 'Address'],
        ['key' => 'actions', 'label' => 'Actions'],
    ];
@endphp

<div class="bg-[#1B1D29]">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-white">
                {{ __('Suppliers') }}
            </h2>
            <a href="{{ route('fournisseurs.create') }}" class="px-4 py-2 text-sm font-medium text-white transition duration-150 ease-in-out bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-700">
                {{ __('Create New Supplier') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-[#2E324A] shadow-xl sm:rounded-lg">
                <div class="p-6 bg-[#2E324A]">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <div>
                                <x-input type="search" wire:model.live="search" placeholder="{{ __('Search suppliers...') }}" class="text-white bg-[#1B1D29] border-gray-700" />
                            </div>
                            <div>
                                <select wire:model.live="perPage" class="text-white bg-[#1B1D29] border-gray-700 rounded-md">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead class="bg-[#1B1D29]">
                                <tr>
                                    @foreach($columns as $column)
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-white">
                                            {{ __($column['label']) }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700 bg-[#2E324A]">
                                @forelse($fournisseurs as $fournisseur)
                                    <tr class="hover:bg-[#1B1D29]">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $fournisseur->nom }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $fournisseur->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $fournisseur->telephone }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $fournisseur->adresse }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <button wire:click="confirmDelete({{ $fournisseur->id }})" class="text-red-400 hover:text-red-500">
                                                {{ __('Delete') }}
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($columns) }}" class="px-6 py-4 whitespace-nowrap text-center">
                                            {{ __('No suppliers found.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $fournisseurs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <x-dialog-modal wire:model.live="showDeleteModal">
        <x-slot name="title" class="text-white">
            {{ __('Delete Supplier') }}
        </x-slot>

        <x-slot name="content">
            <div class="text-white">
                {{ __('Are you sure you want to delete this supplier?') }}
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showDeleteModal', false)" wire:loading.attr="disabled" class="text-white bg-gray-600 hover:bg-gray-500">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ml-3" wire:click="deleteFournisseur" wire:loading.attr="disabled">
                {{ __('Delete Supplier') }}
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>
</div> 