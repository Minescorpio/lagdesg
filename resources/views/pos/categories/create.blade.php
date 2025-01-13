<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Add Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <form action="{{ route('pos.categories.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Name -->
                            <div>
                                <x-label for="name" value="{{ __('Name') }}" />
                                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                                <x-input-error for="name" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div>
                                <x-label for="description" value="{{ __('Description') }}" />
                                <x-textarea id="description" class="block mt-1 w-full" name="description">{{ old('description') }}</x-textarea>
                                <x-input-error for="description" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="is_active" class="flex items-center">
                                    <x-checkbox id="is_active" name="is_active" :checked="old('is_active', true)" />
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Active') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <x-button>
                                {{ __('Create Category') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 