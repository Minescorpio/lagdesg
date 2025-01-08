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
    public $showDeleteModal = false;
    public $categoryToDelete;

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
        $this->categoryToDelete = Category::findOrFail($categoryId);
        $this->showDeleteModal = true;
    }

    public function deleteCategory()
    {
        if ($this->categoryToDelete) {
            // Vérifier si la catégorie a des produits
            if ($this->categoryToDelete->products()->count() > 0) {
                $this->dispatch('notify', [
                    'message' => 'Impossible de supprimer une catégorie avec des produits associés',
                    'type' => 'error'
                ]);
                return;
            }

            // Vérifier si la catégorie a des sous-catégories
            if ($this->categoryToDelete->children()->count() > 0) {
                $this->dispatch('notify', [
                    'message' => 'Impossible de supprimer une catégorie avec des sous-catégories',
                    'type' => 'error'
                ]);
                return;
            }

            $this->categoryToDelete->delete();
            $this->showDeleteModal = false;
            $this->categoryToDelete = null;

            $this->dispatch('notify', [
                'message' => 'Catégorie supprimée avec succès',
                'type' => 'success'
            ]);
        }
    }

    #[Layout('layouts.app')]
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
            ->orderBy('name');

        return view('livewire.categories.index', [
            'categories' => $query->paginate($this->perPage),
        ]);
    }
} 