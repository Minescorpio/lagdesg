<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'sort_order';
    public $sortDirection = 'asc';
    public $showInactive = false;
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'sort_order'],
        'sortDirection' => ['except' => 'asc'],
        'showInactive' => ['except' => false],
        'perPage' => ['except' => 10],
    ];

    protected $listeners = [
        'categoryReordered' => 'handleReorder'
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
        $query = Category::query()
            ->withCount('products')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when(!$this->showInactive, function ($query) {
                $query->where('active', true);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.categories.category-list', [
            'categories' => $query->paginate($this->perPage),
        ]);
    }

    public function toggleActive($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $category->update(['active' => !$category->active]);
        
        $this->dispatch('notify', [
            'message' => __('Category status updated successfully'),
            'type' => 'success'
        ]);
    }

    public function delete($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        
        if ($category->products()->exists()) {
            $this->dispatch('notify', [
                'message' => __('Cannot delete category with associated products.'),
                'type' => 'error'
            ]);
            return;
        }
        
        $category->delete();
        
        $this->dispatch('notify', [
            'message' => __('Category deleted successfully'),
            'type' => 'success'
        ]);
    }

    public function handleReorder($orderedIds)
    {
        foreach ($orderedIds as $order => $categoryId) {
            Category::where('id', $categoryId)
                ->update(['sort_order' => $order]);
        }

        $this->dispatch('notify', [
            'message' => __('Categories reordered successfully'),
            'type' => 'success'
        ]);
    }
} 