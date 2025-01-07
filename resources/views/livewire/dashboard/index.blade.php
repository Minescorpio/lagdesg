<div>
    <div class="min-h-screen bg-gray-100 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="sm:flex sm:items-center sm:justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        {{ __('Dashboard') }}
                    </h2>
                    <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:space-x-6">
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <i class="fas fa-calendar-alt flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"></i>
                            {{ now()->format('d M Y') }}
                        </div>
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <i class="fas fa-sync flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"></i>
                            {{ __('Last updated') }}: {{ now()->format('H:i') }}
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex sm:mt-0 sm:ml-4">
                    <button wire:click="downloadReport" class="btn-secondary mr-3">
                        <i class="fas fa-download mr-2"></i>{{ __('Export Report') }}
                    </button>
                    <a href="{{ route('pos.terminal') }}" class="btn-primary">
                        <i class="fas fa-plus mr-2"></i>{{ __('New Sale') }}
                    </a>
                </div>
            </div>

            <!-- Date Range Filter -->
            <div class="mt-6 bg-white shadow-sm rounded-lg p-4">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-4 flex-grow mr-4">
                        <div class="col-span-2">
                            <select wire:model.live="period" class="form-select">
                                <option value="today">{{ __('Today') }}</option>
                                <option value="yesterday">{{ __('Yesterday') }}</option>
                                <option value="last_7_days">{{ __('Last 7 Days') }}</option>
                                <option value="this_month">{{ __('This Month') }}</option>
                                <option value="last_month">{{ __('Last Month') }}</option>
                                <option value="this_year">{{ __('This Year') }}</option>
                                <option value="custom">{{ __('Custom Range') }}</option>
                            </select>
                        </div>
                        @if($period === 'custom')
                        <div>
                            <input type="date" wire:model.live="startDate" class="form-input" placeholder="{{ __('Start Date') }}">
                        </div>
                        <div>
                            <input type="date" wire:model.live="endDate" class="form-input" placeholder="{{ __('End Date') }}">
                        </div>
                        @endif
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <button wire:click="refresh" class="btn-secondary">
                            <i class="fas fa-sync-alt mr-2"></i>{{ __('Refresh') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Total Sales -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-md bg-blue-500 p-3">
                                    <i class="fas fa-shopping-cart text-white"></i>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Total Sales') }}</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">{{ $totalSales }}</div>
                                        <div class="ml-2 flex items-baseline text-sm font-semibold {{ $salesGrowth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            <i class="fas fa-{{ $salesGrowth >= 0 ? 'arrow-up' : 'arrow-down' }} mr-0.5"></i>
                                            {{ abs($salesGrowth) }}%
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-md bg-green-500 p-3">
                                    <i class="fas fa-dollar-sign text-white"></i>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Total Revenue') }}</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">{{ money($totalRevenue) }}</div>
                                        <div class="ml-2 flex items-baseline text-sm font-semibold {{ $revenueGrowth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            <i class="fas fa-{{ $revenueGrowth >= 0 ? 'arrow-up' : 'arrow-down' }} mr-0.5"></i>
                                            {{ abs($revenueGrowth) }}%
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Average Order Value -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-md bg-yellow-500 p-3">
                                    <i class="fas fa-calculator text-white"></i>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Avg Order Value') }}</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">{{ money($averageOrderValue) }}</div>
                                        <div class="ml-2 flex items-baseline text-sm font-semibold {{ $aovGrowth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            <i class="fas fa-{{ $aovGrowth >= 0 ? 'arrow-up' : 'arrow-down' }} mr-0.5"></i>
                                            {{ abs($aovGrowth) }}%
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items Sold -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-md bg-purple-500 p-3">
                                    <i class="fas fa-box text-white"></i>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Items Sold') }}</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">{{ $totalItemsSold }}</div>
                                        <div class="ml-2 flex items-baseline text-sm font-semibold {{ $itemsGrowth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            <i class="fas fa-{{ $itemsGrowth >= 0 ? 'arrow-up' : 'arrow-down' }} mr-0.5"></i>
                                            {{ abs($itemsGrowth) }}%
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="mt-6 grid grid-cols-1 gap-5 lg:grid-cols-2">
                <!-- Sales Chart -->
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Sales Overview') }}</h3>
                        <div class="flex items-center space-x-3">
                            <select wire:model.live="salesChartType" class="form-select-sm">
                                <option value="daily">{{ __('Daily') }}</option>
                                <option value="weekly">{{ __('Weekly') }}</option>
                                <option value="monthly">{{ __('Monthly') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="h-72">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- Top Products -->
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Top Products') }}</h3>
                        <select wire:model.live="topProductsLimit" class="form-select-sm">
                            <option value="5">Top 5</option>
                            <option value="10">Top 10</option>
                            <option value="15">Top 15</option>
                        </select>
                    </div>
                    <div class="h-72">
                        <canvas id="productsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Additional Stats -->
            <div class="mt-6 grid grid-cols-1 gap-5 lg:grid-cols-2">
                <!-- Payment Methods -->
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Payment Methods') }}</h3>
                    <div class="space-y-4">
                        @foreach($paymentMethods as $method)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="rounded-full p-2 {{ $method['color'] }}">
                                    <i class="fas {{ $method['icon'] }} text-white"></i>
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-900">{{ $method['name'] }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900">{{ money($method['amount']) }}</span>
                                <span class="ml-2 text-sm text-gray-500">({{ $method['percentage'] }}%)</span>
                            </div>
                        </div>
                        <div class="relative pt-1">
                            <div class="overflow-hidden h-2 text-xs flex rounded bg-gray-100">
                                <div class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center {{ $method['bg'] }}"
                                    style="width: {{ $method['percentage'] }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Recent Activity') }}</h3>
                        <a href="{{ route('sales.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            {{ __('View All') }} →
                        </a>
                    </div>
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @foreach($recentActivity as $activity)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                                {{ $activity['type'] === 'sale' ? 'bg-green-500' : 'bg-blue-500' }}">
                                                <i class="fas {{ $activity['type'] === 'sale' ? 'fa-shopping-cart' : 'fa-box' }} text-white"></i>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-900">{{ $activity['description'] }}</p>
                                                @if($activity['customer'])
                                                    <p class="text-sm text-gray-500">{{ $activity['customer'] }}</p>
                                                @endif
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap">
                                                <time class="text-gray-500">{{ $activity['time'] }}</time>
                                                <p class="font-medium text-gray-900">{{ money($activity['amount']) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Low Stock Alert -->
            @if(count($lowStockProducts) > 0)
            <div class="mt-6 bg-white shadow-sm rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Low Stock Alert') }}</h3>
                    <a href="{{ route('stock.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        {{ __('Manage Stock') }} →
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="table-th">{{ __('Product') }}</th>
                                <th scope="col" class="table-th">{{ __('Current Stock') }}</th>
                                <th scope="col" class="table-th">{{ __('Alert Level') }}</th>
                                <th scope="col" class="table-th">{{ __('Status') }}</th>
                                <th scope="col" class="table-th">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($lowStockProducts as $product)
                            <tr>
                                <td class="table-td">
                                    <div class="flex items-center">
                                        @if($product->image)
                                            <img src="{{ Storage::url($product->image) }}" 
                                                alt="{{ $product->name }}"
                                                class="h-8 w-8 rounded-full object-cover">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-box text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div class="ml-4">
                                            <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                            <div class="text-sm text-gray-500">SKU: {{ $product->sku }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="table-td">
                                    <span class="font-medium {{ $product->stock->quantity <= 0 ? 'text-red-600' : 'text-yellow-600' }}">
                                        {{ $product->stock->quantity }}
                                    </span>
                                </td>
                                <td class="table-td">{{ $product->stock->alert_level }}</td>
                                <td class="table-td">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $product->stock->quantity <= 0 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $product->stock->quantity <= 0 ? __('Out of Stock') : __('Low Stock') }}
                                    </span>
                                </td>
                                <td class="table-td">
                                    <a href="{{ route('stock.edit', $product->stock) }}" class="btn-secondary-sm">
                                        {{ __('Adjust Stock') }}
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .table-th {
        @apply px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider;
    }
    .table-td {
        @apply px-6 py-4 whitespace-nowrap text-sm;
    }
    .form-select {
        @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
    }
    .form-select-sm {
        @apply block rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm;
    }
    .form-input {
        @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
    }
    .btn-primary {
        @apply inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
    }
    .btn-secondary {
        @apply inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
    }
    .btn-secondary-sm {
        @apply inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md shadow-sm text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('livewire:load', function () {
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(salesCtx, {
        type: 'line',
        data: @json($salesChartData),
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });

    // Products Chart
    const productsCtx = document.getElementById('productsChart').getContext('2d');
    const productsChart = new Chart(productsCtx, {
        type: 'doughnut',
        data: @json($productsChartData),
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });

    // Update charts when data changes
    Livewire.on('updateCharts', data => {
        salesChart.data = data.salesChartData;
        salesChart.update();
        productsChart.data = data.productsChartData;
        productsChart.update();
    });
});
</script>
@endpush 