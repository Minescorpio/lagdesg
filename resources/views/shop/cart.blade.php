<x-layouts.shop>
    <div class="bg-white rounded-lg shadow-sm">
        <div class="max-w-2xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:max-w-7xl lg:px-8">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">{{ __('Shopping Cart') }}</h1>

            @if(count($cart) > 0)
                <div class="mt-12">
                    <div class="flow-root">
                        <ul role="list" class="-my-6 divide-y divide-gray-200">
                            @foreach($cart as $id => $details)
                                <li class="py-6 flex">
                                    <div class="flex-shrink-0 w-24 h-24 border border-gray-200 rounded-md overflow-hidden">
                                        @if($details['image'])
                                            <img src="{{ asset('storage/' . $details['image']) }}" 
                                                 alt="{{ $details['name'] }}"
                                                 class="w-full h-full object-center object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="ml-4 flex-1 flex flex-col">
                                        <div>
                                            <div class="flex justify-between text-base font-medium text-gray-900">
                                                <h3>{{ $details['name'] }}</h3>
                                                <p class="ml-4">{{ number_format($details['price'] * $details['quantity'], 2) }} €</p>
                                            </div>
                                        </div>
                                        <div class="flex-1 flex items-end justify-between text-sm">
                                            <div class="flex items-center">
                                                <label for="quantity-{{ $id }}" class="mr-2 text-gray-500">{{ __('Quantity') }}</label>
                                                <select id="quantity-{{ $id }}" 
                                                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                        onchange="updateQuantity({{ $id }}, this.value)">
                                                    @for($i = 1; $i <= 10; $i++)
                                                        <option value="{{ $i }}" {{ $details['quantity'] == $i ? 'selected' : '' }}>
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>

                                            <div class="flex">
                                                <button type="button" onclick="removeFromCart({{ $id }})"
                                                        class="font-medium text-indigo-600 hover:text-indigo-500">
                                                    {{ __('Remove') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="border-t border-gray-200 py-6">
                        <div class="flex justify-between text-base font-medium text-gray-900">
                            <p>{{ __('Subtotal') }}</p>
                            <p>{{ number_format(array_reduce($cart, function($carry, $item) {
                                return $carry + ($item['price'] * $item['quantity']);
                            }, 0), 2) }} €</p>
                        </div>
                        <p class="mt-0.5 text-sm text-gray-500">{{ __('Shipping and taxes calculated at checkout.') }}</p>
                        <div class="mt-6">
                            <a href="#" class="flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                {{ __('Checkout') }}
                            </a>
                        </div>
                        <div class="mt-6 flex justify-center text-sm text-center text-gray-500">
                            <p>
                                {{ __('or') }}
                                <a href="{{ route('shop.index') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                                    {{ __('Continue Shopping') }}
                                    <span aria-hidden="true"> &rarr;</span>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('Your cart is empty') }}</h3>
                    <div class="mt-6">
                        <a href="{{ route('shop.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Continue Shopping') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function updateQuantity(productId, quantity) {
            // Add update quantity functionality
        }

        function removeFromCart(productId) {
            // Add remove from cart functionality
        }
    </script>
    @endpush
</x-layouts.shop> 