<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ isset($category) ? __('Edit Category') : __('New Category') }}
            </h2>
            <a href="{{ route('pos.categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Back to Categories') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <form action="{{ isset($category) ? route('pos.categories.update', $category) : route('pos.categories.store') }}" method="POST">
                        @csrf
                        @if(isset($category))
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Name -->
                            <div>
                                <x-label for="name" value="{{ __('Name') }}" />
                                <x-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus
                                    value="{{ old('name', isset($category) ? $category->name : '') }}" />
                                <x-input-error for="name" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div>
                                <x-label for="description" value="{{ __('Description') }}" />
                                <textarea id="description" name="description" rows="3"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', isset($category) ? $category->description : '') }}</textarea>
                                <x-input-error for="description" class="mt-2" />
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-center justify-end mt-4">
                                <x-button class="ml-4">
                                    {{ isset($category) ? __('Update Category') : __('Create Category') }}
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 