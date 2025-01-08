<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('New Category') }}
            </h2>
            <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form wire:submit.prevent="save" class="p-6">
                    <!-- Basic Information -->
                    <div class="space-y-6">
                        <!-- Name -->
                        <div>
                            <x-label for="name" value="{{ __('Name') }}" />
                            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" required autofocus />
                            <x-input-error for="name" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-label for="description" value="{{ __('Description') }}" />
                            <textarea id="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model="description" rows="3"></textarea>
                            <x-input-error for="description" class="mt-2" />
                        </div>

                        <!-- Parent Category -->
                        <div>
                            <x-label for="parent_id" value="{{ __('Parent Category') }}" />
                            <select id="parent_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model="parent_id">
                                <option value="">{{ __('None') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error for="parent_id" class="mt-2" />
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center">
                            <x-checkbox wire:model="active" />
                            <x-label for="active" value="{{ __('Active') }}" class="ml-2" />
                            <x-input-error for="active" class="mt-2" />
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex justify-end space-x-3">
                        <x-secondary-button type="button" wire:click="cancel">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-button type="submit" wire:loading.attr="disabled">
                            {{ __('Create Category') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 