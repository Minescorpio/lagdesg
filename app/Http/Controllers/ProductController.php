<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')
            ->latest()
            ->paginate(10);

        return view('products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::where('active', true)
            ->orderBy('name')
            ->get();

        return view('products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|unique:products',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_weighable' => 'boolean',
            'has_free_price' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'track_stock' => 'boolean',
            'min_stock_alert' => 'nullable|integer|min:0',
            'vat_rate' => 'required|numeric|min:0|max:100',
            'active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', __('Product created successfully'));
    }

    public function show(Product $product): View
    {
        $product->load(['category', 'stocks' => function ($query) {
            $query->latest()->take(10);
        }]);

        return view('products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $categories = Category::where('active', true)
            ->orderBy('name')
            ->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_weighable' => 'boolean',
            'has_free_price' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'track_stock' => 'boolean',
            'min_stock_alert' => 'nullable|integer|min:0',
            'vat_rate' => 'required|numeric|min:0|max:100',
            'active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', __('Product updated successfully'));
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', __('Product deleted successfully'));
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        
        return Product::where('active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('barcode', 'like', "%{$query}%");
            })
            ->with('category')
            ->take(10)
            ->get();
    }
} 