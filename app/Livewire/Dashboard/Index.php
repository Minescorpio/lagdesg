<?php

namespace App\Livewire\Dashboard;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

#[Title('Tableau de bord')]
#[Layout('layouts.app')]
class Index extends Component
{
    public $totalSalesToday = 0;
    public $totalOrders = 0;
    public $lowStockItems = 0;
    public $totalCustomers = 0;
    public $recentSales = [];

    public function mount()
    {
        $this->loadStats();
        $this->loadRecentSales();
    }

    public function loadStats()
    {
        // Total des ventes aujourd'hui
        $this->totalSalesToday = Sale::whereDate('created_at', Carbon::today())
            ->sum('total_amount');

        // Total des commandes
        $this->totalOrders = Sale::count();

        // Articles en stock bas
        $this->lowStockItems = Product::whereRaw('stock_quantity <= minimum_stock')
            ->count();

        // Total des clients
        $this->totalCustomers = Customer::count();
    }

    public function loadRecentSales()
    {
        $this->recentSales = Sale::with('customer')
            ->latest()
            ->take(10)
            ->get();
    }

    public function render()
    {
        return view('dashboard.index');
    }
} 