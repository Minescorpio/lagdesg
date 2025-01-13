<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Add Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <form action="{{ route('pos.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <x-label for="name" value="{{ __('Name') }}" />
                                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                                <x-input-error for="name" class="mt-2" />
                            </div>

                            <!-- SKU -->
                            <div>
                                <x-label for="sku" value="{{ __('SKU') }}" />
                                <x-input id="sku" class="block mt-1 w-full" type="text" name="sku" :value="old('sku')" required />
                                <x-input-error for="sku" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <x-label for="description" value="{{ __('Description') }}" />
                                <x-textarea id="description" class="block mt-1 w-full" name="description">{{ old('description') }}</x-textarea>
                                <x-input-error for="description" class="mt-2" />
                            </div>

                            <!-- Category -->
                            <div>
                                <x-label for="category_id" value="{{ __('Category') }}" />
                                <x-select id="category_id" name="category_id" class="block mt-1 w-full">
                                    <option value="">{{ __('Select Category') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                                <x-input-error for="category_id" class="mt-2" />
                            </div>

                            <!-- Barcode -->
                            <div>
                                <x-label for="barcode" value="{{ __('Barcode') }}" />
                                <x-input id="barcode" class="block mt-1 w-full" type="text" name="barcode" :value="old('barcode')" />
                                <x-input-error for="barcode" class="mt-2" />
                            </div>

                            <!-- Price -->
                            <div>
                                <x-label for="price" value="{{ __('Price') }}" />
                                <x-input id="price" class="block mt-1 w-full" type="number" step="0.01" name="price" :value="old('price')" required />
                                <x-input-error for="price" class="mt-2" />
                            </div>

                            <!-- Cost Price -->
                            <div>
                                <x-label for="cost_price" value="{{ __('Cost Price') }}" />
                                <x-input id="cost_price" class="block mt-1 w-full" type="number" step="0.01" name="cost_price" :value="old('cost_price')" required />
                                <x-input-error for="cost_price" class="mt-2" />
                            </div>

                            <!-- Stock -->
                            <div>
                                <x-label for="stock" value="{{ __('Stock') }}" />
                                <x-input id="stock" class="block mt-1 w-full" type="number" name="stock" :value="old('stock', 0)" required />
                                <x-input-error for="stock" class="mt-2" />
                            </div>

                            <!-- Minimum Stock -->
                            <div>
                                <x-label for="minimum_stock" value="{{ __('Minimum Stock') }}" />
                                <x-input id="minimum_stock" class="block mt-1 w-full" type="number" name="minimum_stock" :value="old('minimum_stock', 0)" required />
                                <x-input-error for="minimum_stock" class="mt-2" />
                            </div>

                            <!-- Image -->
                            <div class="md:col-span-2">
                                <x-label for="image" value="{{ __('Image') }}" />
                                <input type="file" id="image" name="image" class="block w-full text-sm text-gray-500 dark:text-gray-400
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-gray-50 file:text-gray-700
                                    hover:file:bg-gray-100
                                    dark:file:bg-gray-700 dark:file:text-gray-200
                                    dark:hover:file:bg-gray-600" />
                                <x-input-error for="image" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div class="md:col-span-2">
                                <label for="is_active" class="flex items-center">
                                    <x-checkbox id="is_active" name="is_active" :checked="old('is_active', true)" />
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Active') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <x-button>
                                {{ __('Create Product') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 