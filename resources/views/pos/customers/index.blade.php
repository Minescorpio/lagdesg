<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('Customers') }}
            </h2>
            <a href="{{ route('pos.customers.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('New Customer') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <!-- Search -->
                    <div class="mb-6">
                        <form action="{{ route('pos.customers.index') }}" method="GET" class="flex gap-4">
                            <div class="flex-1">
                                <x-input type="search" name="search" placeholder="{{ __('Search customers...') }}" value="{{ request('search') }}" class="block w-full" />
                            </div>
                            <div>
                                <x-button type="submit">
                                    {{ __('Search') }}
                                </x-button>
                            </div>
                        </form>
                    </div>

                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">{{ __('Name') }}</th>
                                    <th scope="col" class="px-6 py-3">{{ __('Email') }}</th>
                                    <th scope="col" class="px-6 py-3">{{ __('Phone') }}</th>
                                    <th scope="col" class="px-6 py-3">{{ __('Total Sales') }}</th>
                                    <th scope="col" class="px-6 py-3">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($customers as $customer)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ $customer->name }}
                                        </td>
                                        <td class="px-6 py-4">{{ $customer->email }}</td>
                                        <td class="px-6 py-4">{{ $customer->phone }}</td>
                                        <td class="px-6 py-4">{{ $customer->sales_count }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex space-x-3">
                                                <a href="{{ route('pos.customers.edit', $customer) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('Edit') }}</a>
                                                <a href="{{ route('pos.customers.history', $customer) }}" class="font-medium text-green-600 dark:text-green-500 hover:underline">{{ __('History') }}</a>
                                                <form action="{{ route('pos.customers.destroy', $customer) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline" onclick="return confirm('{{ __('Are you sure you want to delete this customer?') }}')">
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="5" class="px-6 py-4 text-center">{{ __('No customers found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 