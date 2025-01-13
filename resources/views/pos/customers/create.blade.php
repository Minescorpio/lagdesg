<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Add Customer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <form action="{{ route('pos.customers.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Name -->
                            <div>
                                <x-label for="name" value="{{ __('Name') }}" />
                                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                                <x-input-error for="name" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-label for="email" value="{{ __('Email') }}" />
                                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" />
                                <x-input-error for="email" class="mt-2" />
                            </div>

                            <!-- Phone -->
                            <div>
                                <x-label for="phone" value="{{ __('Phone') }}" />
                                <x-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" />
                                <x-input-error for="phone" class="mt-2" />
                            </div>

                            <!-- Address -->
                            <div>
                                <x-label for="address" value="{{ __('Address') }}" />
                                <x-textarea id="address" class="block mt-1 w-full" name="address">{{ old('address') }}</x-textarea>
                                <x-input-error for="address" class="mt-2" />
                            </div>

                            <!-- Notes -->
                            <div>
                                <x-label for="notes" value="{{ __('Notes') }}" />
                                <x-textarea id="notes" class="block mt-1 w-full" name="notes">{{ old('notes') }}</x-textarea>
                                <x-input-error for="notes" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <x-button>
                                {{ __('Create Customer') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 