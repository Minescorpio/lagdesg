<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Models\Fournisseur;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $perPage = 10;
    public $showDeleteModal = false;
    public $productToDelete;
    public $barcodeInput = '';
    public $fournisseurFilter = '';
    public $showCreateForm = false;

    // Basic Information
    public $name;
    public $description;
    public $barcode;
    public $category_id;
    
    // Price & Stock
    public $price = 0;
    public $cost_price = 0;
    public $vat_rate = 20;
    public $track_stock = true;
    public $alert_quantity = 5;
    public $initial_stock = 0;
    
    // Additional Options
    public $weighable = false;
    public $free_price = false;
    public $active = true;
    
    // Image
    public $image;

    // Fournisseur
    public $fournisseur_id;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'barcode' => 'nullable|string|max:50|unique:products,barcode',
        'category_id' => 'nullable|exists:categories,id',
        'price' => 'required|numeric|min:0',
        'cost_price' => 'required|numeric|min:0',
        'vat_rate' => 'required|numeric|min:0|max:100',
        'track_stock' => 'boolean',
        'alert_quantity' => 'required_if:track_stock,true|numeric|min:0',
        'initial_stock' => 'required_if:track_stock,true|numeric|min:0',
        'weighable' => 'boolean',
        'free_price' => 'boolean',
        'active' => 'boolean',
        'image' => 'nullable|image|max:1024',
        'fournisseur_id' => 'nullable|exists:fournisseurs,id',
    ];

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
            if ($this->productToDelete->sales()->count() > 0) {
                $this->dispatch('notify', [
                    'message' => __('Cannot delete a product with associated sales'),
                    'type' => 'error'
                ]);
                return;
            }

            $this->productToDelete->delete();
            $this->showDeleteModal = false;
            $this->productToDelete = null;

            $this->dispatch('notify', [
                'message' => __('Product deleted successfully'),
                'type' => 'success'
            ]);
        }
    }

    public function showCreate()
    {
        $this->resetValidation();
        $this->reset([
            'name', 'description', 'barcode', 'category_id', 
            'price', 'cost_price', 'vat_rate', 'track_stock', 
            'alert_quantity', 'initial_stock', 'weighable', 
            'free_price', 'active', 'image', 'fournisseur_id'
        ]);
        $this->showCreateForm = true;
    }

    public function cancelCreate()
    {
        $this->showCreateForm = false;
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'description' => $this->description,
                'barcode' => $this->barcode,
                'category_id' => $this->category_id,
                'price' => $this->price,
                'cost_price' => $this->cost_price,
                'vat_rate' => $this->vat_rate,
                'track_stock' => $this->track_stock,
                'alert_quantity' => $this->alert_quantity,
                'weighable' => $this->weighable,
                'free_price' => $this->free_price,
                'active' => $this->active,
                'fournisseur_id' => $this->fournisseur_id,
            ];

            if ($this->image) {
                $data['image_path'] = $this->image->store('products', 'public');
            }

            $product = Product::create($data);

            if ($this->track_stock && $this->initial_stock > 0) {
                $product->stocks()->create([
                    'type' => 'in',
                    'quantity' => $this->initial_stock,
                    'unit_price' => $this->cost_price,
                    'reference' => 'Initial Stock',
                    'user_id' => auth()->id()
                ]);
            }

            $this->showCreateForm = false;
            $this->dispatch('notify', [
                'message' => __('Product created successfully'),
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => __('Error creating product: ') . $e->getMessage(),
                'type' => 'error'
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
                'message' => __('Product not found for this barcode')  
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
            'categories' => Category::where('active', true)->orderBy('name')->get(),
        ]);
    }
} 