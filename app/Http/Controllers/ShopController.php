<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $categories = Category::where('active', true)->orderBy('name')->get();
        $products = Product::where('active', true)
            ->where('condition', 'new')
            ->with('category')
            ->orderBy('name')
            ->paginate(12);

        return view('shop.index', compact('categories', 'products'));
    }

    public function category(Category $category)
    {
        $categories = Category::where('active', true)->orderBy('name')->get();
        $products = Product::where('active', true)
            ->where('condition', 'new')
            ->where('category_id', $category->id)
            ->with('category')
            ->orderBy('name')
            ->paginate(12);

        return view('shop.category', compact('categories', 'category', 'products'));
    }

    public function product(Product $product)
    {
        if (!$product->active || $product->condition !== 'new') {
            abort(404);
        }

        return view('shop.product', compact('product'));
    }

    public function addToCart(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);
        
        if (!$product->active || $product->condition !== 'new') {
            return response()->json([
                'success' => false,
                'message' => 'Product is not available'
            ], 400);
        }

        // Get current cart from session
        $cart = session()->get('cart', []);
        
        // Add/update product in cart
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'image' => $product->image_path
            ];
        }
        
        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully',
            'cart_count' => count($cart)
        ]);
    }

    public function cart()
    {
        $cart = session()->get('cart', []);
        return view('shop.cart', compact('cart'));
    }
}
