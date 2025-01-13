<?php
// ... existing code ...
?>
<div>
    <!-- Categories Navigation -->
    <nav class="bg-[#2E324A]/80 border-t border-gray-700 backdrop-blur-sm mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-center h-16">
                <div class="flex space-x-8">
                    <button wire:click="selectCategory(null)" 
                            class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium
                                   {{ is_null($selectedCategory) ? 'bg-[#1B1D29] text-white' : '' }}">
                        {{ __('All') }}
                    </button>
                    @foreach($categories as $category)
                        <button wire:click="selectCategory('{{ $category->slug }}')" 
                                class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium
                                       {{ $selectedCategory === $category->slug ? 'bg-[#1B1D29] text-white' : '' }}">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </nav>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($products as $product)
            <div class="bg-[#2E324A]/80 rounded-lg shadow-lg overflow-hidden border border-gray-700 hover:border-blue-500/40 transition-all duration-300">
                <div class="aspect-w-16 aspect-h-9">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-white mb-2">{{ $product->name }}</h3>
                    <p class="text-gray-400 text-sm mb-4">{{ Str::limit($product->description, 100) }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-white font-bold">{{ number_format($product->price, 2) }} €</span>
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                            {{ __('Add to Cart') }}
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $products->links() }}
    </div>
</div>
