<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class ProductList extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $selectedCategory = '';
    public $showInactive = false;
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'selectedCategory' => ['except' => ''],
        'showInactive' => ['except' => false],
        'perPage' => ['except' => 10],
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::query()
            ->with('category')
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
            ->when(!$this->showInactive, function ($query) {
                $query->where('is_active', true);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $categories = Category::where('active', true)
            ->orderBy('name')
            ->get();

        return view('livewire.products.product-list', [
            'products' => $query->paginate($this->perPage),
            'categories' => $categories,
        ]);
    }

    public function toggleActive($productId)
    {
        $product = Product::findOrFail($productId);
        $product->update(['is_active' => !$product->is_active]);
        $this->dispatch('notify', [
            'message' => __('Product status updated successfully'),
            'type' => 'success'
        ]);
    }

    public function delete($productId)
    {
        $product = Product::findOrFail($productId);
        
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        
        $product->delete();
        
        $this->dispatch('notify', [
            'message' => __('Product deleted successfully'),
            'type' => 'success'
        ]);
    }
} 