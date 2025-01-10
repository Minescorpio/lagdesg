<?php

namespace App\Livewire;

use App\Helpers\CurrencyHelper;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Dashboard extends Component
{
    public function getTotalSalesToday()
    {
        return CurrencyHelper::format(Sale::whereDate('created_at', today())->sum('total_amount'));
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('dashboard', [
            'totalSalesToday' => $this->getTotalSalesToday(),
            'totalOrders' => Sale::whereDate('created_at', today())->count(),
            'lowStockItems' => Product::lowStock()->count(),
            'totalCustomers' => Customer::count(),
            'recentSales' => Sale::with('customer')->latest()->take(5)->get()
        ]);
    }
} 