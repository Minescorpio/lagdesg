<?php

namespace App\Livewire\Dashboard;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Helpers\CurrencyHelper;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

class Index extends Component
{
    #[Layout('components.layouts.app')]
    public function render()
    {
        $today = Carbon::today();

        $totalSalesToday = CurrencyHelper::format(
            Sale::whereDate('created_at', $today)
                ->where('status', 'completed')
                ->sum('total_amount') ?? 0
        );

        $totalOrders = Sale::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->count();

        $lowStockItems = Product::lowStock()->count();

        $totalCustomers = Customer::count();

        $recentSales = Sale::with('customer')
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalSalesToday',
            'totalOrders',
            'lowStockItems',
            'totalCustomers',
            'recentSales'
        ));
    }
} 