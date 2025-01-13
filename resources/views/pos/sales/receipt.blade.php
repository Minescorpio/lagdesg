<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Sale Receipt') }} #{{ $sale->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .receipt {
            width: 80mm;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .store-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .store-info {
            margin-bottom: 3px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        .sale-info {
            margin-bottom: 15px;
        }
        .sale-info p {
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 3px 0;
        }
        .amount {
            text-align: right;
        }
        .total {
            font-weight: bold;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <div class="store-name">{{ config('app.name') }}</div>
            <div class="store-info">123 Store Street</div>
            <div class="store-info">City, State 12345</div>
            <div class="store-info">Tel: (123) 456-7890</div>
        </div>

        <div class="divider"></div>

        <div class="sale-info">
            <p><strong>{{ __('Receipt') }}:</strong> #{{ $sale->id }}</p>
            <p><strong>{{ __('Date') }}:</strong> {{ $sale->created_at->format('M d, Y H:i') }}</p>
            <p><strong>{{ __('Customer') }}:</strong> {{ $sale->customer ? $sale->customer->name : __('Walk-in Customer') }}</p>
            <p><strong>{{ __('Payment') }}:</strong> {{ __(ucfirst($sale->payment_method)) }}</p>
        </div>

        <div class="divider"></div>

        <table>
            <thead>
                <tr>
                    <th>{{ __('Item') }}</th>
                    <th>{{ __('Qty') }}</th>
                    <th class="amount">{{ __('Price') }}</th>
                    <th class="amount">{{ __('Total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td class="amount">{{ money($item->price) }}</td>
                        <td class="amount">{{ money($item->subtotal) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total">
                    <td colspan="3">{{ __('Total') }}</td>
                    <td class="amount">{{ money($sale->total_amount) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="divider"></div>

        <div class="footer">
            <p>{{ __('Thank you for your purchase!') }}</p>
            <p>{{ __('Please come again') }}</p>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">
            {{ __('Print Receipt') }}
        </button>
    </div>
</body>
</html> 