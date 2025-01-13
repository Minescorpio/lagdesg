<x-layouts.app>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Customer') }}
            </h2>
            <a href="{{ route('customers.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <form action="{{ route('customers.update', $customer) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-label for="name" value="{{ __('Name') }}" />
                            <x-input id="name" name="name" type="text" class="mt-1 block w-full" 
                                    value="{{ old('name', $customer->name) }}" required autofocus />
                            <x-input-error for="name" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="email" value="{{ __('Email') }}" />
                            <x-input id="email" name="email" type="email" class="mt-1 block w-full" 
                                    value="{{ old('email', $customer->email) }}" required />
                            <x-input-error for="email" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="phone" value="{{ __('Phone') }}" />
                            <x-input id="phone" name="phone" type="text" class="mt-1 block w-full" 
                                    value="{{ old('phone', $customer->phone) }}" />
                            <x-input-error for="phone" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="address" value="{{ __('Address') }}" />
                            <textarea id="address" name="address" 
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    rows="3">{{ old('address', $customer->address) }}</textarea>
                            <x-input-error for="address" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="loyalty_points" value="{{ __('Loyalty Points') }}" />
                            <x-input id="loyalty_points" name="loyalty_points" type="number" class="mt-1 block w-full" 
                                    value="{{ old('loyalty_points', $customer->loyalty_points) }}" required />
                            <x-input-error for="loyalty_points" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="credit_balance" value="{{ __('Credit Balance') }}" />
                            <x-input id="credit_balance" name="credit_balance" type="number" step="0.01" class="mt-1 block w-full" 
                                    value="{{ old('credit_balance', $customer->credit_balance) }}" required />
                            <x-input-error for="credit_balance" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-button>
                                {{ __('Update Customer') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app> 