<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(): View
    {
        $stocks = Stock::with(['product'])
            ->latest()
            ->paginate(10);

        return view('stocks.index', compact('stocks'));
    }

    public function create(): View
    {
        $products = Product::where('active', true)
            ->where('track_stock', true)
            ->orderBy('name')
            ->get();

        return view('stocks.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric',
            'movement_type' => 'required|in:in,out,adjustment,loss,return',
            'reason' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'unit_price' => 'nullable|numeric|min:0',
            'reference' => 'nullable|string|max:255',
            'metadata' => 'nullable|array',
        ]);

        $validated['user_id'] = auth()->id();

        DB::transaction(function () use ($validated) {
            Stock::create($validated);

            // Update product stock based on movement type
            $product = Product::findOrFail($validated['product_id']);
            
            switch ($validated['movement_type']) {
                case 'in':
                case 'return':
                    $product->increment('current_stock', $validated['quantity']);
                    break;
                case 'out':
                case 'loss':
                    $product->decrement('current_stock', $validated['quantity']);
                    break;
                case 'adjustment':
                    $product->update(['current_stock' => $validated['quantity']]);
                    break;
            }
        });

        return redirect()->route('stocks.index')
            ->with('success', __('Stock movement recorded successfully'));
    }

    public function show(Stock $stock): View
    {
        $stock->load('product', 'user');
        return view('stocks.show', compact('stock'));
    }

    public function lowStock(): View
    {
        $products = Product::where('track_stock', true)
            ->whereRaw('current_stock <= min_stock_alert')
            ->with(['category', 'stocks' => function ($query) {
                $query->latest()->take(5);
            }])
            ->paginate(10);

        return view('stocks.low-stock', compact('products'));
    }

    public function stockHistory(Product $product): View
    {
        $movements = Stock::where('product_id', $product->id)
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('stocks.history', compact('product', 'movements'));
    }

    public function bulkAdjustment(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'adjustments' => 'required|array',
            'adjustments.*.product_id' => 'required|exists:products,id',
            'adjustments.*.quantity' => 'required|numeric',
            'adjustments.*.reason' => 'required|string',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['adjustments'] as $adjustment) {
                Stock::create([
                    'product_id' => $adjustment['product_id'],
                    'quantity' => $adjustment['quantity'],
                    'movement_type' => 'adjustment',
                    'reason' => $adjustment['reason'],
                    'user_id' => auth()->id(),
                ]);

                Product::where('id', $adjustment['product_id'])
                    ->update(['current_stock' => $adjustment['quantity']]);
            }
        });

        return back()->with('success', __('Bulk stock adjustment completed successfully'));
    }

    public function export(Request $request)
    {
        $query = Stock::query()->with(['product', 'user']);

        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->has('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }

        return response()->streamDownload(function () use ($query) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'Date',
                'Product',
                'Movement Type',
                'Quantity',
                'Location',
                'Reason',
                'Reference',
                'User',
            ]);

            // Data
            $query->chunk(1000, function ($stocks) use ($file) {
                foreach ($stocks as $stock) {
                    fputcsv($file, [
                        $stock->created_at->format('Y-m-d H:i:s'),
                        $stock->product->name,
                        $stock->movement_type,
                        $stock->quantity,
                        $stock->location,
                        $stock->reason,
                        $stock->reference,
                        $stock->user->name,
                    ]);
                }
            });

            fclose($file);
        }, 'stock-movements.csv');
    }
} 