<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TerminalController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->get();
        $customers = Customer::all();
        $products = Product::with('category')
            ->where('is_active', true)
            ->paginate(12);

        return view('pos.terminal', compact('categories', 'customers', 'products'));
    }

    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        
        if ($product->stock < 1) {
            return response()->json([
                'message' => __('Product is out of stock.')
            ], 422);
        }

        $cart = session()->get('cart', []);
        
        if (isset($cart[$product->id])) {
            if ($cart[$product->id]['quantity'] + 1 > $product->stock) {
                return response()->json([
                    'message' => __('Not enough stock available.')
                ], 422);
            }
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'message' => __('Product added to cart successfully.'),
            'cart' => $cart
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->product_id])) {
            unset($cart[$request->product_id]);
            session()->put('cart', $cart);
        }

        return response()->json([
            'message' => __('Product removed from cart successfully.'),
            'cart' => $cart
        ]);
    }

    public function updateCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        if (isset($cart[$request->product_id])) {
            if ($request->quantity > $product->stock) {
                return response()->json([
                    'message' => __('Not enough stock available.')
                ], 422);
            }

            if ($request->quantity < 1) {
                unset($cart[$request->product_id]);
            } else {
                $cart[$request->product_id]['quantity'] = $request->quantity;
            }

            session()->put('cart', $cart);
        }

        return response()->json([
            'message' => __('Cart updated successfully.'),
            'cart' => $cart
        ]);
    }

    public function clearCart()
    {
        session()->forget('cart');

        return response()->json([
            'message' => __('Cart cleared successfully.'),
            'cart' => []
        ]);
    }

    public function processSale(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,card,transfer',
            'amount_received' => 'required_if:payment_method,cash|numeric|min:0',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return response()->json([
                'message' => __('Cart is empty.')
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Create sale
            $sale = Sale::create([
                'customer_id' => $request->customer_id,
                'payment_method' => $request->payment_method,
                'amount_received' => $request->amount_received,
                'notes' => $request->notes,
                'status' => 'completed'
            ]);

            $total = 0;

            // Create sale items and update stock
            foreach ($cart as $item) {
                $product = Product::findOrFail($item['id']);
                
                if ($product->stock < $item['quantity']) {
                    throw new \Exception(__('Not enough stock for :product', ['product' => $product->name]));
                }

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $product->price * $item['quantity']
                ]);

                $product->decrement('stock', $item['quantity']);
                $total += $product->price * $item['quantity'];
            }

            // Update sale total
            $sale->update([
                'total_amount' => $total,
                'change_amount' => $request->payment_method === 'cash' ? $request->amount_received - $total : 0
            ]);

            DB::commit();

            // Clear cart
            session()->forget('cart');

            return response()->json([
                'message' => __('Sale completed successfully.'),
                'sale_id' => $sale->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }
} 