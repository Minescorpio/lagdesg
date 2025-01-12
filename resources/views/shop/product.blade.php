<x-layouts.shop>
    <div class="bg-[#2E324A] rounded-lg shadow-sm">
        <div class="max-w-2xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:max-w-7xl lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
                <!-- Image gallery -->
                <div class="flex flex-col">
                    <div class="w-full aspect-w-1 aspect-h-1 bg-[#1B1D29] rounded-lg overflow-hidden xl:aspect-w-7 xl:aspect-h-8">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-center object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-[#1B1D29]">
                                <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Product info -->
                <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                    <h1 class="text-3xl font-extrabold tracking-tight text-gray-200">{{ $product->name }}</h1>

                    <div class="mt-3">
                        <h2 class="sr-only">{{ __('Product information') }}</h2>
                        <p class="text-3xl text-gray-200">{{ number_format($product->price, 2) }} €</p>
                    </div>

                    <div class="mt-6">
                        <h3 class="sr-only">{{ __('Description') }}</h3>
                        <div class="text-base text-gray-300 space-y-6">
                            <p>{{ $product->description }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <div class="flex items-center">
                            <h2 class="text-sm font-medium text-gray-200">{{ __('Category') }}:</h2>
                            <p class="ml-2 text-sm text-gray-400">{{ $product->category->name }}</p>
                        </div>
                    </div>

                    <form class="mt-6">
                        <!-- Quantity -->
                        <div class="mt-10 flex sm:flex-col1">
                            <label for="quantity" class="sr-only">{{ __('Quantity') }}</label>
                            <select id="quantity" name="quantity" class="max-w-full rounded-md border border-gray-600 bg-[#1B1D29] py-1.5 text-base leading-5 font-medium text-gray-200 text-left shadow-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>

                            <button type="button"
                                    onclick="addToCart({{ $product->id }}, document.getElementById('quantity').value)"
                                    class="ml-4 flex-1 bg-indigo-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-indigo-500">
                                {{ __('Add to cart') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function addToCart(productId, quantity) {
            fetch(`/shop/cart/add/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    quantity: parseInt(quantity)
                })
            })
            .then(response => response.json())
            .then(updateCart)
            .catch(error => console.error('Error:', error));
        }
    </script>
    @endpush
</x-layouts.shop> 