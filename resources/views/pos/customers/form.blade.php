<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ isset($customer) ? __('Edit Customer') : __('New Customer') }}
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
                    <form action="{{ isset($customer) ? route('pos.customers.update', $customer) : route('pos.customers.store') }}" method="POST">
                        @csrf
                        @if(isset($customer))
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <x-label for="name" value="{{ __('Name') }}" />
                                <x-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus
                                    value="{{ old('name', isset($customer) ? $customer->name : '') }}" />
                                <x-input-error for="name" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-label for="email" value="{{ __('Email') }}" />
                                <x-input id="email" name="email" type="email" class="mt-1 block w-full"
                                    value="{{ old('email', isset($customer) ? $customer->email : '') }}" />
                                <x-input-error for="email" class="mt-2" />
                            </div>

                            <!-- Phone -->
                            <div>
                                <x-label for="phone" value="{{ __('Phone') }}" />
                                <x-input id="phone" name="phone" type="tel" class="mt-1 block w-full"
                                    value="{{ old('phone', isset($customer) ? $customer->phone : '') }}" />
                                <x-input-error for="phone" class="mt-2" />
                            </div>

                            <!-- Address -->
                            <div class="md:col-span-2">
                                <x-label for="address" value="{{ __('Address') }}" />
                                <textarea id="address" name="address" rows="3"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('address', isset($customer) ? $customer->address : '') }}</textarea>
                                <x-input-error for="address" class="mt-2" />
                            </div>

                            <!-- Notes -->
                            <div class="md:col-span-2">
                                <x-label for="notes" value="{{ __('Notes') }}" />
                                <textarea id="notes" name="notes" rows="3"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('notes', isset($customer) ? $customer->notes : '') }}</textarea>
                                <x-input-error for="notes" class="mt-2" />
                            </div>

                            <!-- Submit Button -->
                            <div class="md:col-span-2 flex items-center justify-end mt-4">
                                <x-button class="ml-4">
                                    {{ isset($customer) ? __('Update Customer') : __('Create Customer') }}
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 