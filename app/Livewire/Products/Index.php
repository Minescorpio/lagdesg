<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $showDeleteModal = false;
    public $productToDelete;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($productId)
    {
        $this->productToDelete = Product::findOrFail($productId);
        $this->showDeleteModal = true;
    }

    public function deleteProduct()
    {
        if ($this->productToDelete) {
            // Vérifier si le produit a des ventes associées
            if ($this->productToDelete->sales()->count() > 0) {
                $this->dispatch('notify', [
                    'message' => 'Impossible de supprimer un produit avec des ventes associées',
                    'type' => 'error'
                ]);
                return;
            }

            $this->productToDelete->delete();
            $this->showDeleteModal = false;
            $this->productToDelete = null;

            $this->dispatch('notify', [
                'message' => 'Produit supprimé avec succès',
                'type' => 'success'
            ]);
        }
    }

    #[Layout('layouts.app')]
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
            ->orderBy('name');

        return view('livewire.products.index', [
            'products' => $query->paginate($this->perPage),
        ]);
    }
} 