<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('Sales History') }}
            </h2>
            <a href="{{ route('pos.terminal') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('New Sale') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">{{ __('Date') }}</th>
                                    <th scope="col" class="px-6 py-3">{{ __('Invoice') }}</th>
                                    <th scope="col" class="px-6 py-3">{{ __('Customer') }}</th>
                                    <th scope="col" class="px-6 py-3">{{ __('Items') }}</th>
                                    <th scope="col" class="px-6 py-3">{{ __('Total') }}</th>
                                    <th scope="col" class="px-6 py-3">{{ __('Payment') }}</th>
                                    <th scope="col" class="px-6 py-3">{{ __('Status') }}</th>
                                    <th scope="col" class="px-6 py-3">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sales as $sale)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4">{{ $sale->created_at->format('M d, Y H:i') }}</td>
                                        <td class="px-6 py-4">#{{ $sale->id }}</td>
                                        <td class="px-6 py-4">{{ $sale->customer ? $sale->customer->name : __('Walk-in Customer') }}</td>
                                        <td class="px-6 py-4">{{ $sale->items->count() }}</td>
                                        <td class="px-6 py-4">{{ money($sale->total_amount) }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ __(ucfirst($sale->payment_method)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $sale->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ __(ucfirst($sale->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex space-x-3">
                                                <a href="{{ route('pos.sales.show', $sale) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('View') }}</a>
                                                <a href="{{ route('pos.sales.receipt', $sale) }}" target="_blank" class="font-medium text-green-600 dark:text-green-500 hover:underline">{{ __('Receipt') }}</a>
                                                @if($sale->status === 'completed')
                                                    <form action="{{ route('pos.sales.void', $sale) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline" onclick="return confirm('{{ __('Are you sure you want to void this sale?') }}')">
                                                            {{ __('Void') }}
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="8" class="px-6 py-4 text-center">{{ __('No sales found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $sales->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 