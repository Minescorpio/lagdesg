<div>
    <!-- Screen Display Version -->
    <div class="screen-only bg-gray-100 min-h-screen py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Actions -->
            <div class="mb-6 px-4 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">{{ __('Sale Receipt') }}</h1>
                <div class="flex items-center space-x-2">
                    <button onclick="window.print()" class="btn-secondary">
                        <i class="fas fa-print mr-2"></i>{{ __('Print') }}
                    </button>
                    <button wire:click="emailReceipt" class="btn-secondary">
                        <i class="fas fa-envelope mr-2"></i>{{ __('Email') }}
                    </button>
                    <a href="{{ route('pos.terminal') }}" class="btn-primary">
                        <i class="fas fa-cash-register mr-2"></i>{{ __('New Sale') }}
                    </a>
                </div>
            </div>

            <!-- Receipt Card -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <!-- Store Info -->
                <div class="p-6 text-center border-b border-gray-200">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" 
                        class="h-12 mx-auto mb-4">
                    <h2 class="text-xl font-bold text-gray-900">{{ config('app.name') }}</h2>
                    <p class="text-gray-600">{{ config('pos.address') }}</p>
                    <p class="text-gray-600">{{ config('pos.phone') }}</p>
                    <p class="text-gray-600">{{ config('pos.email') }}</p>
                </div>

                <!-- Receipt Details -->
                <div class="p-6 border-b border-gray-200">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Receipt No') }}:</p>
                            <p class="font-medium">{{ $sale->receipt_number }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">{{ __('Date') }}:</p>
                            <p class="font-medium">{{ $sale->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Cashier') }}:</p>
                            <p class="font-medium">{{ $sale->cashier->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">{{ __('Payment Method') }}:</p>
                            <p class="font-medium">{{ ucfirst($sale->payment_method) }}</p>
                        </div>
                        @if($sale->customer)
                        <div class="col-span-2">
                            <p class="text-sm text-gray-600">{{ __('Customer') }}:</p>
                            <p class="font-medium">{{ $sale->customer->name }}</p>
                            @if($sale->customer->loyalty_points)
                            <p class="text-sm text-blue-600 mt-1">
                                {{ __('Points Earned') }}: +{{ $sale->loyalty_points_earned }}
                            </p>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Items -->
                <div class="p-6 border-b border-gray-200">
                    <table class="w-full">
                        <thead>
                            <tr class="text-sm text-gray-600 border-b border-gray-200">
                                <th class="pb-3 text-left">{{ __('Item') }}</th>
                                <th class="pb-3 text-right">{{ __('Qty') }}</th>
                                <th class="pb-3 text-right">{{ __('Price') }}</th>
                                <th class="pb-3 text-right">{{ __('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($sale->items as $item)
                            <tr>
                                <td class="py-3">
                                    <p class="font-medium">{{ $item->product->name }}</p>
                                    <p class="text-sm text-gray-500">SKU: {{ $item->product->sku }}</p>
                                </td>
                                <td class="py-3 text-right">{{ $item->quantity }}</td>
                                <td class="py-3 text-right">{{ money($item->unit_price) }}</td>
                                <td class="py-3 text-right">{{ money($item->total_price) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Totals -->
                <div class="p-6 bg-gray-50">
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ __('Subtotal') }}</span>
                            <span class="font-medium">{{ money($sale->subtotal) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ __('Tax') }} ({{ $sale->tax_rate }}%)</span>
                            <span class="font-medium">{{ money($sale->tax_amount) }}</span>
                        </div>
                        @if($sale->discount_amount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">
                                {{ __('Discount') }} 
                                @if($sale->discount_type === 'percentage')
                                    ({{ $sale->discount_percentage }}%)
                                @endif
                            </span>
                            <span class="font-medium text-red-600">-{{ money($sale->discount_amount) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200">
                            <span>{{ __('Total') }}</span>
                            <span>{{ money($sale->total_amount) }}</span>
                        </div>
                        @if($sale->payment_method === 'cash')
                        <div class="flex justify-between text-sm pt-2">
                            <span class="text-gray-600">{{ __('Cash Received') }}</span>
                            <span class="font-medium">{{ money($sale->cash_received) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ __('Change') }}</span>
                            <span class="font-medium">{{ money($sale->change_amount) }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-6 text-center text-gray-600 text-sm border-t border-gray-200">
                    <p class="mb-2">{{ __('Thank you for your purchase!') }}</p>
                    @if($sale->customer && $sale->customer->loyalty_points)
                    <p class="text-blue-600">
                        {{ __('Current Points Balance') }}: {{ number_format($sale->customer->loyalty_points) }}
                    </p>
                    @endif
                    <p class="mt-2">
                        {{ config('pos.receipt_footer') }}
                    </p>
                    @if($sale->receipt_number)
                    <div class="mt-4">
                        {!! QrCode::size(100)->generate($sale->receipt_number) !!}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Print Version -->
    <div class="print-only">
        <div class="text-center">
            <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" 
                class="h-12 mx-auto mb-4">
            <h2 class="text-xl font-bold">{{ config('app.name') }}</h2>
            <p>{{ config('pos.address') }}</p>
            <p>{{ config('pos.phone') }}</p>
            <p>{{ config('pos.email') }}</p>
        </div>

        <div class="my-4">
            <div class="flex justify-between text-sm">
                <div>
                    <p>{{ __('Receipt No') }}: {{ $sale->receipt_number }}</p>
                    <p>{{ __('Cashier') }}: {{ $sale->cashier->name }}</p>
                </div>
                <div class="text-right">
                    <p>{{ __('Date') }}: {{ $sale->created_at->format('d M Y H:i') }}</p>
                    <p>{{ __('Payment') }}: {{ ucfirst($sale->payment_method) }}</p>
                </div>
            </div>
            @if($sale->customer)
            <p class="mt-2">{{ __('Customer') }}: {{ $sale->customer->name }}</p>
            @endif
        </div>

        <div class="border-t border-b border-gray-300 my-4">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th class="text-left py-2">{{ __('Item') }}</th>
                        <th class="text-right py-2">{{ __('Qty') }}</th>
                        <th class="text-right py-2">{{ __('Price') }}</th>
                        <th class="text-right py-2">{{ __('Total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $item)
                    <tr>
                        <td class="py-1">{{ $item->product->name }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">{{ money($item->unit_price) }}</td>
                        <td class="text-right">{{ money($item->total_price) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-sm">
            <div class="flex justify-between">
                <span>{{ __('Subtotal') }}</span>
                <span>{{ money($sale->subtotal) }}</span>
            </div>
            <div class="flex justify-between">
                <span>{{ __('Tax') }} ({{ $sale->tax_rate }}%)</span>
                <span>{{ money($sale->tax_amount) }}</span>
            </div>
            @if($sale->discount_amount > 0)
            <div class="flex justify-between">
                <span>
                    {{ __('Discount') }} 
                    @if($sale->discount_type === 'percentage')
                        ({{ $sale->discount_percentage }}%)
                    @endif
                </span>
                <span>-{{ money($sale->discount_amount) }}</span>
            </div>
            @endif
            <div class="flex justify-between font-bold mt-2 pt-2 border-t">
                <span>{{ __('Total') }}</span>
                <span>{{ money($sale->total_amount) }}</span>
            </div>
            @if($sale->payment_method === 'cash')
            <div class="flex justify-between mt-2">
                <span>{{ __('Cash Received') }}</span>
                <span>{{ money($sale->cash_received) }}</span>
            </div>
            <div class="flex justify-between">
                <span>{{ __('Change') }}</span>
                <span>{{ money($sale->change_amount) }}</span>
            </div>
            @endif
        </div>

        <div class="text-center text-sm mt-4">
            <p>{{ __('Thank you for your purchase!') }}</p>
            @if($sale->customer && $sale->customer->loyalty_points)
            <p class="mt-1">
                {{ __('Points Earned') }}: +{{ $sale->loyalty_points_earned }}
                ({{ __('Balance') }}: {{ number_format($sale->customer->loyalty_points) }})
            </p>
            @endif
            <p class="mt-2">{{ config('pos.receipt_footer') }}</p>
            @if($sale->receipt_number)
            <div class="mt-4">
                {!! QrCode::size(100)->generate($sale->receipt_number) !!}
            </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    @media screen {
        .print-only {
            display: none;
        }
    }
    @media print {
        .screen-only {
            display: none;
        }
        .print-only {
            display: block;
            max-width: 80mm;
            margin: 0 auto;
            font-size: 12px;
            line-height: 1.2;
        }
        @page {
            margin: 0;
            size: 80mm 297mm;
        }
    }
    .btn-primary {
        @apply inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
    }
    .btn-secondary {
        @apply inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
    }
</style>
@endpush 