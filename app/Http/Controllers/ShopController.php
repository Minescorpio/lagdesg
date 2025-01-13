<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(12);
        return view('shop.index', compact('products'));
    }

    public function products()
    {
        $products = Product::with('category')->latest()->paginate(12);
        return view('shop.products', compact('products'));
    }

    public function product(Product $product)
    {
        return view('shop.product', compact('product'));
    }

    public function adminProducts()
    {
        $this->authorize('view products');
        $products = Product::with('category')->latest()->paginate(15);
        return view('shop.admin.products', compact('products'));
    }

    public function adminOrders()
    {
        $this->authorize('view orders');
        $orders = Order::with(['customer', 'products'])->latest()->paginate(15);
        return view('shop.admin.orders', compact('orders'));
    }

    public function adminCustomers()
    {
        $this->authorize('view customers');
        $customers = Customer::latest()->paginate(15);
        return view('shop.admin.customers', compact('customers'));
    }

    public function adminReports()
    {
        $this->authorize('view reports');
        return view('shop.admin.reports');
    }

    public function adminSettings()
    {
        $this->authorize('admin');
        return view('shop.admin.settings');
    }
}
