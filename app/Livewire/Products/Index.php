<?php

namespace App\Livewire\Products;

use App\Helpers\CurrencyHelper;
use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $selectedCategory = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $confirmingProductDeletion = false;
    public $productIdToDelete;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'selectedCategory' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmDelete($productId)
    {
        $this->productIdToDelete = $productId;
        $this->confirmingProductDeletion = true;
    }

    public function deleteProduct()
    {
        $product = Product::findOrFail($this->productIdToDelete);
        $product->delete();
        $this->confirmingProductDeletion = false;
        
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => __('Product deleted successfully.')
        ]);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $products = Product::query()
            ->with(['category', 'stocks'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('barcode', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $categories = Category::where('active', true)->orderBy('name')->get();

        // Format currency for each product
        $products->getCollection()->transform(function ($product) {
            $product->formatted_price = CurrencyHelper::format($product->price);
            $product->formatted_cost = CurrencyHelper::format($product->cost_price);
            return $product;
        });

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
} 