<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class PosController extends Controller
{
    public function terminal(): View
    {
        $products = Product::where('active', true)
            ->with('category')
            ->get();

        $customers = Customer::where('active', true)
            ->orderBy('last_name')
            ->get();

        return view('pos.terminal', compact('products', 'customers'));
    }

    public function getProducts(Request $request): JsonResponse
    {
        $query = Product::query()->with('category');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        $products = $query->where('active', true)->get();

        return response()->json($products);
    }

    public function addToCart(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.001',
            'price' => 'required|numeric|min:0'
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $currentStock = $product->current_stock;

        if ($product->track_stock && $currentStock < $validated['quantity']) {
            return response()->json([
                'error' => __('Insufficient stock. Available: :stock', ['stock' => $currentStock])
            ], 422);
        }

        // Add to cart logic here (using session or cart package)
        // This is just a placeholder response
        return response()->json([
            'message' => __('Product added to cart'),
            'cart_total' => 0
        ]);
    }

    public function updateCart(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'items.*.price' => 'required|numeric|min:0'
        ]);

        // Update cart logic here
        return response()->json([
            'message' => __('Cart updated'),
            'cart_total' => 0
        ]);
    }

    public function removeFromCart(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        // Remove from cart logic here
        return response()->json([
            'message' => __('Product removed from cart'),
            'cart_total' => 0
        ]);
    }

    public function clearCart(): JsonResponse
    {
        // Clear cart logic here
        return response()->json([
            'message' => __('Cart cleared')
        ]);
    }

    public function completeSale(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'items.*.price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,bank_transfer',
            'payment_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Create sale logic here
            $sale = new Sale(); // Placeholder
            
            DB::commit();

            return response()->json([
                'message' => __('Sale completed successfully'),
                'sale_id' => $sale->id,
                'receipt_url' => route('pos.receipt', $sale)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => __('Error completing sale: :message', ['message' => $e->getMessage()])
            ], 500);
        }
    }

    public function receipt(Sale $sale): View
    {
        return view('pos.receipt', compact('sale'));
    }
} 