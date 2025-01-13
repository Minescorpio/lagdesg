@php
    $columns = [
        ['key' => 'number', 'label' => 'Order #'],
        ['key' => 'supplier', 'label' => 'Supplier'],
        ['key' => 'date', 'label' => 'Date'],
        ['key' => 'total', 'label' => 'Total'],
        ['key' => 'status', 'label' => 'Status'],
        ['key' => 'actions', 'label' => 'Actions'],
    ];
@endphp

<div class="bg-[#1B1D29]">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-white">
                {{ __('Purchase Orders') }}
            </h2>
            <a href="{{ route('purchases.create') }}" class="px-4 py-2 text-sm font-medium text-white transition duration-150 ease-in-out bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-700">
                {{ __('New Purchase Order') }}
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
                                <x-input type="search" wire:model.live="search" placeholder="{{ __('Search purchase orders...') }}" class="text-white bg-[#1B1D29] border-gray-700" />
                            </div>
                            <div>
                                <select wire:model.live="perPage" class="text-white bg-[#1B1D29] border-gray-700 rounded-md">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            <div>
                                <select wire:model.live="status" class="text-white bg-[#1B1D29] border-gray-700 rounded-md">
                                    <option value="">{{ __('All Statuses') }}</option>
                                    <option value="pending">{{ __('Pending') }}</option>
                                    <option value="ordered">{{ __('Ordered') }}</option>
                                    <option value="received">{{ __('Received') }}</option>
                                    <option value="cancelled">{{ __('Cancelled') }}</option>
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
                                @forelse($purchases as $purchase)
                                    <tr class="hover:bg-[#1B1D29]">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->supplier->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->total }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($purchase->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($purchase->status === 'ordered') bg-blue-100 text-blue-800
                                                @elseif($purchase->status === 'received') bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ __(ucfirst($purchase->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <a href="{{ route('purchases.show', $purchase) }}" class="text-indigo-400 hover:text-indigo-500">
                                                {{ __('View') }}
                                            </a>
                                            <button wire:click="confirmDelete({{ $purchase->id }})" class="text-red-400 hover:text-red-500">
                                                {{ __('Delete') }}
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($columns) }}" class="px-6 py-4 whitespace-nowrap text-center">
                                            {{ __('No purchase orders found.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $purchases->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <x-dialog-modal wire:model.live="showDeleteModal">
        <x-slot name="title" class="text-white">
            {{ __('Delete Purchase Order') }}
        </x-slot>

        <x-slot name="content">
            <div class="text-white">
                {{ __('Are you sure you want to delete this purchase order?') }}
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showDeleteModal', false)" wire:loading.attr="disabled" class="text-white bg-gray-600 hover:bg-gray-500">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ml-3" wire:click="deletePurchase" wire:loading.attr="disabled">
                {{ __('Delete Purchase Order') }}
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>
</div> 