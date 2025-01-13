<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get today's sales total amount
        $todaySales = Sale::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total_amount');

        // Get today's orders count
        $todayOrders = Sale::whereDate('created_at', today())
            ->where('status', 'completed')
            ->count();

        // Get low stock products count
        $lowStockProducts = Product::whereColumn('stock', '<=', 'minimum_stock')
            ->where('is_active', true)
            ->count();

        // Get total customers
        $totalCustomers = Customer::count();

        // Get recent sales
        $recentSales = Sale::with('customer')
            ->latest()
            ->take(5)
            ->get();

        // Get low stock alerts
        $lowStockAlerts = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->where('is_active', true)
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'todaySales',
            'todayOrders',
            'lowStockProducts',
            'totalCustomers',
            'recentSales',
            'lowStockAlerts'
        ));
    }
} 