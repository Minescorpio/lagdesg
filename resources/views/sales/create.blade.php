<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('New Sale') }}
            </h2>
            <a href="{{ route('sales.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Customer Selection -->
                    <div class="mb-6">
                        <label for="customer_id" class="block text-sm font-medium text-gray-700">{{ __('Customer') }}</label>
                        <div class="mt-1">
                            <select id="customer_id" wire:model="customer_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">{{ __('Select Customer') }}</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Products List -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Products') }}</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Product') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Quantity') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Unit Price') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Total') }}</th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">{{ __('Actions') }}</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($items as $index => $item)
                                        <tr>
                                            <td class="px-6 py-4">
                                                <select wire:model="items.{{ $index }}.product_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                                    <option value="">{{ __('Select Product') }}</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}">{{ $product->name }} - {{ $product->formatted_price }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-6 py-4">
                                                <input type="number" wire:model="items.{{ $index }}.quantity" min="1" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ isset($items[$index]['unit_price']) ? CurrencyHelper::format($items[$index]['unit_price']) : '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ isset($items[$index]['total']) ? CurrencyHelper::format($items[$index]['total']) : '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-right text-sm font-medium">
                                                <button wire:click="removeItem({{ $index }})" class="text-red-600 hover:text-red-900">
                                                    {{ __('Remove') }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            <button wire:click="addItem" type="button" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Add Product') }}
                            </button>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">{{ __('Payment Method') }}</label>
                            <select id="payment_method" wire:model="payment_method" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">{{ __('Select Payment Method') }}</option>
                                <option value="cash">{{ __('Cash') }}</option>
                                <option value="card">{{ __('Card') }}</option>
                                <option value="transfer">{{ __('Transfer') }}</option>
                            </select>
                        </div>

                        <div>
                            <label for="payment_amount" class="block text-sm font-medium text-gray-700">{{ __('Payment Amount') }}</label>
                            <input type="number" id="payment_amount" wire:model="payment_amount" step="0.01" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        </div>
                    </div>

                    <!-- Totals -->
                    <div class="mt-8 flex justify-end">
                        <div class="text-right">
                            <div class="text-sm text-gray-500">{{ __('Total') }}: {{ $total }}</div>
                            @if($payment_amount)
                                <div class="text-sm text-gray-500">{{ __('Change') }}: {{ $change }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mt-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700">{{ __('Notes') }}</label>
                        <textarea id="notes" wire:model="notes" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex justify-end space-x-3">
                        <x-secondary-button wire:click="cancel">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-button wire:click="save" wire:loading.attr="disabled">
                            {{ __('Complete Sale') }}
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 