<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('Customer History') }} - {{ $customer->name }}
            </h2>
            <a href="{{ route('pos.customers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Back to Customers') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <!-- Customer Details -->
                    <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Contact Information') }}</h3>
                            <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                <p>{{ __('Email') }}: {{ $customer->email ?: __('Not provided') }}</p>
                                <p>{{ __('Phone') }}: {{ $customer->phone ?: __('Not provided') }}</p>
                                <p>{{ __('Address') }}: {{ $customer->address ?: __('Not provided') }}</p>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Sales Summary') }}</h3>
                            <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                <p>{{ __('Total Sales') }}: {{ $customer->sales_count }}</p>
                                <p>{{ __('Total Amount') }}: {{ $customer->sales_sum_total_amount ? money($customer->sales_sum_total_amount) : money(0) }}</p>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Additional Information') }}</h3>
                            <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                <p>{{ __('Notes') }}: {{ $customer->notes ?: __('No notes') }}</p>
                                <p>{{ __('Customer since') }}: {{ $customer->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Sales History -->
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Recent Sales') }}</h3>
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">{{ __('Date') }}</th>
                                    <th scope="col" class="px-6 py-3">{{ __('Invoice') }}</th>
                                    <th scope="col" class="px-6 py-3">{{ __('Items') }}</th>
                                    <th scope="col" class="px-6 py-3">{{ __('Total') }}</th>
                                    <th scope="col" class="px-6 py-3">{{ __('Status') }}</th>
                                    <th scope="col" class="px-6 py-3">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($customer->sales as $sale)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4">{{ $sale->created_at->format('M d, Y H:i') }}</td>
                                        <td class="px-6 py-4">#{{ $sale->id }}</td>
                                        <td class="px-6 py-4">{{ $sale->items->count() }}</td>
                                        <td class="px-6 py-4">{{ money($sale->total_amount) }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $sale->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex space-x-3">
                                                <a href="{{ route('pos.sales.show', $sale) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('View') }}</a>
                                                <a href="{{ route('pos.sales.receipt', $sale) }}" class="font-medium text-green-600 dark:text-green-500 hover:underline">{{ __('Receipt') }}</a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="6" class="px-6 py-4 text-center">{{ __('No sales found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 