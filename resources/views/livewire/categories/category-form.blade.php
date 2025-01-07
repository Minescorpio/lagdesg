<div>
    <form wire:submit="save" class="space-y-8">
        <div class="bg-white shadow-sm rounded-lg">
            <!-- Header -->
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ $categoryId ? __('Edit Category') : __('New Category') }}
                    </h3>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('categories.index') }}" class="btn-secondary">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn-primary">
                            {{ $categoryId ? __('Update') : __('Create') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 gap-6">
                    <!-- Basic Information -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">{{ __('Basic Information') }}</h4>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Name -->
                            <div>
                                <label for="name" class="form-label required">{{ __('Name') }}</label>
                                <input type="text" wire:model="name" id="name" class="form-input" required>
                                @error('name') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Parent Category -->
                            <div>
                                <label for="parent_id" class="form-label">{{ __('Parent Category') }}</label>
                                <select wire:model="parent_id" id="parent_id" class="form-select">
                                    <option value="">{{ __('None') }}</option>
                                    @foreach($parentCategories as $parentCategory)
                                        <option value="{{ $parentCategory->id }}"
                                            @if($categoryId && $parentCategory->id === $categoryId) disabled @endif>
                                            {{ $parentCategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_id') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Description -->
                            <div class="sm:col-span-2">
                                <label for="description" class="form-label">{{ __('Description') }}</label>
                                <textarea wire:model="description" id="description" rows="3" class="form-textarea"></textarea>
                                @error('description') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Appearance -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">{{ __('Appearance') }}</h4>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Color -->
                            <div>
                                <label for="color" class="form-label">{{ __('Color') }}</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <div class="h-4 w-4 rounded"
                                            style="background-color: {{ $color }}"></div>
                                    </div>
                                    <input type="text" wire:model="color" id="color"
                                        class="form-input pl-10"
                                        placeholder="#HEX">
                                </div>
                                @error('color') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Icon -->
                            <div>
                                <label for="icon" class="form-label">{{ __('Icon') }}</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="{{ $icon ?? 'fas fa-folder' }} text-gray-400"></i>
                                    </div>
                                    <input type="text" wire:model="icon" id="icon"
                                        class="form-input pl-10"
                                        placeholder="fas fa-...">
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ __('Use Font Awesome classes') }} (e.g. fas fa-tag)
                                </p>
                                @error('icon') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Sort Order -->
                            <div>
                                <label for="sort_order" class="form-label">{{ __('Sort Order') }}</label>
                                <input type="number" wire:model="sort_order" id="sort_order"
                                    class="form-input" min="0">
                                @error('sort_order') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <!-- Active Status -->
                            <div class="flex items-center">
                                <button type="button" wire:click="$toggle('active')"
                                    class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 {{ $active ? 'bg-blue-600' : 'bg-gray-200' }}"
                                    role="switch" aria-checked="{{ $active ? 'true' : 'false' }}">
                                    <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                                <span class="ml-3">
                                    <span class="text-sm font-medium text-gray-900">{{ __('Active') }}</span>
                                    <span class="text-sm text-gray-500">{{ __('Category is visible and can be used') }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<style>
    .form-label {
        @apply block text-sm font-medium text-gray-700;
    }
    .form-label.required::after {
        content: "*";
        @apply text-red-500 ml-1;
    }
    .form-input {
        @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
    }
    .form-select {
        @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
    }
    .form-textarea {
        @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
    }
    .form-error {
        @apply mt-1 text-sm text-red-600;
    }
    .btn-primary {
        @apply inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
    }
    .btn-secondary {
        @apply inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
    }
</style>
@endpush 