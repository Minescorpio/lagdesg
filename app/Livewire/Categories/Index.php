<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $confirmingCategoryDeletion = false;
    public $categoryIdToDelete;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($categoryId)
    {
        $this->categoryIdToDelete = $categoryId;
        $this->confirmingCategoryDeletion = true;
    }

    public function deleteCategory()
    {
        $category = Category::findOrFail($this->categoryIdToDelete);
        
        // Check if category has products
        if ($category->products()->count() > 0) {
            // Uncategorize products
            $category->products()->update(['category_id' => null]);
        }

        $category->delete();
        $this->confirmingCategoryDeletion = false;
        
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => __('Category deleted successfully.')
        ]);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $categories = Category::query()
            ->withCount('products')
            ->with('parent')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('categories.index', compact('categories'));
    }
} 