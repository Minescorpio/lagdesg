<div class="bg-[#1B1D29]">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-white">
                {{ __('Purchase Order Details') }} - {{ $purchase->number }}
            </h2>
            <div class="flex items-center space-x-4">
                <div class="relative" x-data="{ open: false }">
                    <x-button @click="open = !open" class="bg-indigo-600 hover:bg-indigo-500">
                        {{ __('Change Status') }}
                    </x-button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 z-10 mt-2 w-48 rounded-md shadow-lg">
                        <div class="rounded-md bg-[#2E324A] shadow-xs">
                            <div class="py-1" role="menu" aria-orientation="vertical">
                                <button wire:click="updateStatus('pending')" class="block px-4 py-2 text-sm text-white hover:bg-[#1B1D29] w-full text-left" role="menuitem">{{ __('Pending') }}</button>
                                <button wire:click="updateStatus('ordered')" class="block px-4 py-2 text-sm text-white hover:bg-[#1B1D29] w-full text-left" role="menuitem">{{ __('Ordered') }}</button>
                                <button wire:click="updateStatus('received')" class="block px-4 py-2 text-sm text-white hover:bg-[#1B1D29] w-full text-left" role="menuitem">{{ __('Received') }}</button>
                                <button wire:click="updateStatus('cancelled')" class="block px-4 py-2 text-sm text-white hover:bg-[#1B1D29] w-full text-left" role="menuitem">{{ __('Cancelled') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <x-button wire:click="$emit('print')" class="bg-gray-600 hover:bg-gray-500">
                    {{ __('Print') }}
                </x-button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-[#2E324A] shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Purchase Order Information -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <h3 class="text-lg font-medium text-white mb-4">{{ __('Purchase Order Information') }}</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-400">{{ __('Status') }}</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($purchase->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($purchase->status === 'ordered') bg-blue-100 text-blue-800
                                            @elseif($purchase->status === 'received') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ __(ucfirst($purchase->status)) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-400">{{ __('Date') }}</dt>
                                    <dd class="mt-1 text-white">{{ $purchase->created_at->format('d/m/Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-400">{{ __('Total') }}</dt>
                                    <dd class="mt-1 text-white">{{ number_format($purchase->total, 2) }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-white mb-4">{{ __('Supplier Information') }}</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-400">{{ __('Name') }}</dt>
                                    <dd class="mt-1 text-white">{{ $purchase->supplier->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-400">{{ __('Email') }}</dt>
                                    <dd class="mt-1 text-white">{{ $purchase->supplier->email }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-400">{{ __('Phone') }}</dt>
                                    <dd class="mt-1 text-white">{{ $purchase->supplier->phone }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Items -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-white mb-4">{{ __('Items') }}</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-[#1B1D29]">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('Product') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('Quantity') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('Price') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ __('Subtotal') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700 bg-[#2E324A]">
                                    @foreach($purchase->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-white">{{ $item->product->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-white">{{ $item->quantity }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-white">{{ number_format($item->price, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-white">{{ number_format($item->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-right text-white font-medium">{{ __('Total:') }}</td>
                                        <td class="px-6 py-4 text-white font-medium">{{ number_format($purchase->total, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    @if($purchase->note)
                        <!-- Note -->
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-white mb-4">{{ __('Note') }}</h3>
                            <div class="bg-[#1B1D29] rounded-md p-4">
                                <p class="text-white">{{ $purchase->note }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div> 