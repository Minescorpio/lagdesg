<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Models\Fournisseur;
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
    public $barcodeInput = '';
    public $fournisseurFilter = '';

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

    public function handleBarcodeScan()
    {
        if (empty($this->barcodeInput)) {
            return;
        }

        $product = Product::where('barcode', $this->barcodeInput)->first();

        if ($product) {
            return redirect()->route('products.show', $product);
        } else {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => __('Produit non trouvé pour ce code-barres')  
            ]);
        }

        $this->barcodeInput = '';
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $query = Product::query()
            ->with(['category', 'fournisseur'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('barcode', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->barcodeInput, function ($query) {
                $query->where('barcode', $this->barcodeInput);  
            })
            ->when($this->fournisseurFilter, function ($query) {
                $query->where('fournisseur_id', $this->fournisseurFilter);
            })
            ->orderBy('name');

        return view('livewire.products.index', [
            'products' => $query->paginate($this->perPage),
            'fournisseurs' => Fournisseur::orderBy('nom')->get(),
        ]);
    }
} 