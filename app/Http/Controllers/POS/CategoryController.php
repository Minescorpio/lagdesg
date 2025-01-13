<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('products')
            ->latest()
            ->paginate(10);

        return view('pos.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('pos.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Category::create($validated);

        return redirect()->route('pos.categories.index')
            ->with('success', __('Category created successfully'));
    }

    public function edit(Category $category): View
    {
        return view('pos.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $category->update($validated);

        return redirect()->route('pos.categories.index')
            ->with('success', __('Category updated successfully'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return back()->with('error', __('Cannot delete category with associated products'));
        }

        $category->delete();

        return redirect()->route('pos.categories.index')
            ->with('success', __('Category deleted successfully'));
    }
} 