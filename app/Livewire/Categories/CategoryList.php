<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class CategoryList extends Component
{
    use WithPagination;

    public $search = '';
    public $showInactive = false;
    public $perPage = 10;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $showDeleteModal = false;
    public $categoryToDelete;

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
            'message' => 'Statut de la catégorie mis à jour avec succès',
            'type' => 'success'
        ]);
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

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
} 