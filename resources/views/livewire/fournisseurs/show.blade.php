<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-white">
                {{ $fournisseur->nom }}
            </h2>
            <a href="{{ route('fournisseurs.index') }}" class="px-4 py-2 text-sm font-medium text-white transition duration-150 ease-in-out bg-gray-600 border border-transparent rounded-md hover:bg-gray-500 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-700">
                {{ __('Back to Suppliers') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-[#2E324A] shadow-xl sm:rounded-lg">
                <div class="p-6 bg-[#2E324A]">
                    <!-- Supplier Details -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-white">{{ __('Contact Information') }}</h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <span class="text-sm font-medium text-gray-400">{{ __('Email') }}</span>
                                        <p class="mt-1 text-sm text-white">{{ $fournisseur->email ?: __('Not provided') }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-400">{{ __('Phone') }}</span>
                                        <p class="mt-1 text-sm text-white">{{ $fournisseur->telephone ?: __('Not provided') }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-400">{{ __('Address') }}</span>
                                        <p class="mt-1 text-sm text-white">{{ $fournisseur->adresse ?: __('Not provided') }}</p>
                                    </div>
                                </div>
                            </div>

                            @if($fournisseur->notes)
                                <div>
                                    <h3 class="text-lg font-medium text-white">{{ __('Notes') }}</h3>
                                    <p class="mt-2 text-sm text-gray-300">{{ $fournisseur->notes }}</p>
                                </div>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-white">{{ __('Products') }}</h3>
                            <div class="mt-4">
                                @if($fournisseur->products->count() > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-700">
                                            <thead>
                                                <tr>
                                                    <th class="px-6 py-3 text-left">
                                                        <span class="text-xs font-medium tracking-wider text-gray-300 uppercase">
                                                            {{ __('Name') }}
                                                        </span>
                                                    </th>
                                                    <th class="px-6 py-3 text-left">
                                                        <span class="text-xs font-medium tracking-wider text-gray-300 uppercase">
                                                            {{ __('Price') }}
                                                        </span>
                                                    </th>
                                                    <th class="px-6 py-3 text-left">
                                                        <span class="text-xs font-medium tracking-wider text-gray-300 uppercase">
                                                            {{ __('Stock') }}
                                                        </span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-700">
                                                @foreach($fournisseur->products as $product)
                                                    <tr class="hover:bg-[#1B1D29]">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-white">{{ $product->name }}</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-300">{{ number_format($product->price, 2) }} €</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            @if($product->track_stock)
                                                                <div class="text-sm {{ $product->stock < $product->alert_quantity ? 'text-red-400' : 'text-gray-300' }}">
                                                                    {{ $product->stock }}
                                                                </div>
                                                            @else
                                                                <div class="text-sm text-gray-500">{{ __('Not tracked') }}</div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-sm text-gray-400">{{ __('No products found for this supplier.') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 