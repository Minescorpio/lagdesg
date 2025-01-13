<?php

namespace App\Http\Controllers\POS;

use App\Models\Sale;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SaleController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('view-sales');
        
        $sales = Sale::with(['customer', 'user', 'products'])
            ->latest()
            ->paginate(15);
            
        return view('pos.sales.index', compact('sales'));
    }

    public function show(Sale $sale)
    {
        $this->authorize('view-sales');
        
        $sale->load(['customer', 'user', 'products']);
        return view('pos.sales.show', compact('sale'));
    }

    public function receipt(Sale $sale)
    {
        $this->authorize('view-sales');
        
        $sale->load(['customer', 'products']);
        return view('pos.sales.receipt', compact('sale'));
    }

    public function void(Sale $sale)
    {
        $this->authorize('void-sales');
        
        // Restore stock
        foreach ($sale->products as $product) {
            $product->increment('stock', $product->pivot->quantity);
        }

        $sale->update([
            'status' => 'voided',
            'voided_at' => now(),
            'voided_by' => auth()->id()
        ]);

        return back()->with('success', __('Sale has been voided successfully'));
    }

    public function report(Request $request)
    {
        $this->authorize('view-reports');
        
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        $sales = Sale::with(['customer', 'products'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get();

        $totalSales = $sales->sum('total_amount');
        $totalProducts = $sales->sum(function($sale) {
            return $sale->products->sum('pivot.quantity');
        });
        $averageOrderValue = $sales->count() > 0 ? $totalSales / $sales->count() : 0;

        $paymentMethods = $sales->groupBy('payment_method')
            ->map(function($group) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('total_amount')
                ];
            });

        return view('pos.sales.report', compact(
            'sales',
            'totalSales',
            'totalProducts',
            'averageOrderValue',
            'paymentMethods',
            'startDate',
            'endDate'
        ));
    }
} 