<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('Sale Details') }} #{{ $sale->id }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('pos.sales.receipt', $sale) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Print Receipt') }}
                </a>
                <a href="{{ route('pos.sales.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Back to Sales') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <!-- Sale Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Sale Information') }}</h3>
                            <div class="space-y-2">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-medium">{{ __('Date') }}:</span>
                                    {{ $sale->created_at->format('M d, Y H:i') }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-medium">{{ __('Status') }}:</span>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $sale->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ __(ucfirst($sale->status)) }}
                                    </span>
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-medium">{{ __('Payment Method') }}:</span>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ __(ucfirst($sale->payment_method)) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Customer Information') }}</h3>
                            <div class="space-y-2">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-medium">{{ __('Name') }}:</span>
                                    {{ $sale->customer ? $sale->customer->name : __('Walk-in Customer') }}
                                </p>
                                @if($sale->customer)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <span class="font-medium">{{ __('Email') }}:</span>
                                        {{ $sale->customer->email }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <span class="font-medium">{{ __('Phone') }}:</span>
                                        {{ $sale->customer->phone }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Sale Items -->
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">{{ __('Product') }}</th>
                                    <th scope="col" class="px-6 py-3">{{ __('Price') }}</th>
                                    <th scope="col" class="px-6 py-3">{{ __('Quantity') }}</th>
                                    <th scope="col" class="px-6 py-3">{{ __('Subtotal') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->items as $item)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4">{{ $item->product->name }}</td>
                                        <td class="px-6 py-4">{{ money($item->price) }}</td>
                                        <td class="px-6 py-4">{{ $item->quantity }}</td>
                                        <td class="px-6 py-4">{{ money($item->subtotal) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="font-semibold text-gray-900 dark:text-white">
                                    <td class="px-6 py-3 text-base" colspan="3">{{ __('Total') }}</td>
                                    <td class="px-6 py-3">{{ money($sale->total_amount) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 