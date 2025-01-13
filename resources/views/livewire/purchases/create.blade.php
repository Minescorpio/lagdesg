<div class="bg-[#1B1D29]">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-white">
                {{ __('Create Purchase Order') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-[#2E324A] shadow-xl sm:rounded-lg">
                <form wire:submit.prevent="save" class="p-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Purchase Order Number -->
                        <div>
                            <x-label for="number" value="{{ __('Purchase Order Number') }}" class="text-white" />
                            <x-input id="number" type="text" wire:model="number" class="block w-full mt-1 text-white bg-[#1B1D29] border-gray-700" readonly />
                            @error('number') <span class="mt-1 text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Supplier -->
                        <div>
                            <x-label for="fournisseur_id" value="{{ __('Supplier') }}" class="text-white" />
                            <select id="fournisseur_id" wire:model="fournisseur_id" class="block w-full mt-1 text-white bg-[#1B1D29] border-gray-700 rounded-md shadow-sm">
                                <option value="">{{ __('Select Supplier') }}</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            @error('fournisseur_id') <span class="mt-1 text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <x-label for="status" value="{{ __('Status') }}" class="text-white" />
                            <select id="status" wire:model="status" class="block w-full mt-1 text-white bg-[#1B1D29] border-gray-700 rounded-md shadow-sm">
                                <option value="pending">{{ __('Pending') }}</option>
                                <option value="ordered">{{ __('Ordered') }}</option>
                                <option value="received">{{ __('Received') }}</option>
                                <option value="cancelled">{{ __('Cancelled') }}</option>
                            </select>
                            @error('status') <span class="mt-1 text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Items -->
                    <div class="mt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-white">{{ __('Items') }}</h3>
                            <x-button type="button" wire:click="addItem" class="bg-indigo-600 hover:bg-indigo-500">
                                {{ __('Add Item') }}
                            </x-button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-[#1B1D29]">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('Product') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('Quantity') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('Price') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('Subtotal') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-white uppercase tracking-wider">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700 bg-[#2E324A]">
                                    @foreach($items as $index => $item)
                                        <tr>
                                            <td class="px-6 py-4">
                                                <select wire:model="items.{{ $index }}.product_id" class="block w-full text-white bg-[#1B1D29] border-gray-700 rounded-md shadow-sm">
                                                    <option value="">{{ __('Select Product') }}</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error("items.{$index}.product_id") <span class="mt-1 text-sm text-red-500">{{ $message }}</span> @enderror
                                            </td>
                                            <td class="px-6 py-4">
                                                <x-input type="number" wire:model="items.{{ $index }}.quantity" class="block w-full text-white bg-[#1B1D29] border-gray-700" min="1" />
                                                @error("items.{$index}.quantity") <span class="mt-1 text-sm text-red-500">{{ $message }}</span> @enderror
                                            </td>
                                            <td class="px-6 py-4">
                                                <x-input type="number" wire:model="items.{{ $index }}.price" class="block w-full text-white bg-[#1B1D29] border-gray-700" step="0.01" min="0" />
                                                @error("items.{$index}.price") <span class="mt-1 text-sm text-red-500">{{ $message }}</span> @enderror
                                            </td>
                                            <td class="px-6 py-4 text-white">
                                                {{ number_format($item['quantity'] * $item['price'], 2) }}
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <button type="button" wire:click="removeItem({{ $index }})" class="text-red-400 hover:text-red-500">
                                                    {{ __('Remove') }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-right text-white font-medium">{{ __('Total:') }}</td>
                                        <td class="px-6 py-4 text-white font-medium">{{ number_format($total, 2) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @error('items') <span class="mt-1 text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Note -->
                    <div class="mt-6">
                        <x-label for="note" value="{{ __('Note') }}" class="text-white" />
                        <textarea id="note" wire:model="note" class="block w-full mt-1 text-white bg-[#1B1D29] border-gray-700 rounded-md shadow-sm" rows="3"></textarea>
                        @error('note') <span class="mt-1 text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end mt-6 space-x-4">
                        <x-button type="button" class="bg-gray-600 hover:bg-gray-500" wire:click="$emit('cancel')">
                            {{ __('Cancel') }}
                        </x-button>
                        <x-button type="submit" class="bg-indigo-600 hover:bg-indigo-500">
                            {{ __('Create Purchase Order') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 