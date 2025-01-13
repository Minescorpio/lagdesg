<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ isset($product) ? __('Edit Product') : __('New Product') }}
            </h2>
            <a href="{{ route('pos.products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Back to Products') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <form action="{{ isset($product) ? route('pos.products.update', $product) : route('pos.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($product))
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <x-label for="name" value="{{ __('Name') }}" />
                                <x-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus
                                    value="{{ old('name', isset($product) ? $product->name : '') }}" />
                                <x-input-error for="name" class="mt-2" />
                            </div>

                            <!-- Category -->
                            <div>
                                <x-label for="category_id" value="{{ __('Category') }}" />
                                <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="">{{ __('Select Category') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', isset($product) ? $product->category_id : '') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error for="category_id" class="mt-2" />
                            </div>

                            <!-- Price -->
                            <div>
                                <x-label for="price" value="{{ __('Price') }}" />
                                <x-input id="price" name="price" type="number" step="0.01" min="0" class="mt-1 block w-full" required
                                    value="{{ old('price', isset($product) ? $product->price : '') }}" />
                                <x-input-error for="price" class="mt-2" />
                            </div>

                            <!-- Stock -->
                            <div>
                                <x-label for="stock" value="{{ __('Stock') }}" />
                                <x-input id="stock" name="stock" type="number" min="0" class="mt-1 block w-full" required
                                    value="{{ old('stock', isset($product) ? $product->stock : '') }}" />
                                <x-input-error for="stock" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <x-label for="description" value="{{ __('Description') }}" />
                                <textarea id="description" name="description" rows="3"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', isset($product) ? $product->description : '') }}</textarea>
                                <x-input-error for="description" class="mt-2" />
                            </div>

                            <!-- Active Status -->
                            <div class="md:col-span-2">
                                <label for="is_active" class="inline-flex items-center">
                                    <input id="is_active" type="checkbox" name="is_active" value="1" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                        {{ old('is_active', isset($product) ? $product->is_active : true) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Active') }}</span>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <div class="md:col-span-2 flex items-center justify-end mt-4">
                                <x-button class="ml-4">
                                    {{ isset($product) ? __('Update Product') : __('Create Product') }}
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 