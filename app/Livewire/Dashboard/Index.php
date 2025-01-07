<?php

namespace App\Livewire\Dashboard;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $todaySales;
    public $totalOrders;
    public $lowStockItems;
    public $totalCustomers;
    public $recentSales;

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        // Calculate today's sales
        $this->todaySales = Sale::whereDate('created_at', today())
            ->sum('total_amount');

        // Get total orders count
        $this->totalOrders = Sale::count();

        // Get low stock items count
        $this->lowStockItems = Product::lowStock()->count();

        // Get total customers
        $this->totalCustomers = Customer::count();

        // Get recent sales
        $this->recentSales = Sale::with('customer')
            ->latest()
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard.index');
    }
} 