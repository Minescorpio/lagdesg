<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public $selectedCategory = null;
    public $categories;

    public function mount()
    {
        $this->categories = Category::active()->orderBy('name')->get();
    }

    public function selectCategory($slug = null)
    {
        $this->selectedCategory = $slug;
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::query()->with('category')->active();
        
        if ($this->selectedCategory) {
            $query->whereHas('category', function($q) {
                $q->where('slug', $this->selectedCategory);
            });
        }

        $products = $query->latest()->paginate(12);

        return view('livewire.shop.product-list', [
            'products' => $products
        ]);
    }
}
