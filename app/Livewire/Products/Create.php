<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Models\Category;
use App\Models\Stock;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    use WithFileUploads;

    // Propriétés de base
    public $name = '';
    public $description = '';
    public $barcode = '';
    public $category_id = '';
    
    // Prix et TVA
    public $price = 0;
    public $cost_price = 0;
    public $vat_rate = 20;
    
    // Stock
    public $track_stock = true;
    public $min_stock_alert = 0;
    public $initial_stock = 0;
    
    // Options
    public $is_weighable = false;
    public $has_free_price = false;
    public $active = true;
    
    // Image
    public $image;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'barcode' => 'nullable|string|max:50|unique:products,barcode',
        'category_id' => 'nullable|exists:categories,id',
        'price' => 'required|numeric|min:0',
        'cost_price' => 'nullable|numeric|min:0',
        'vat_rate' => 'required|numeric|in:0,2.1,5.5,10,20',
        'min_stock_alert' => 'nullable|numeric|min:0',
        'initial_stock' => 'nullable|numeric|min:0',
        'image' => 'nullable|image|max:1024', // Max 1MB
    ];

    public function mount()
    {
        // Initialisation si nécessaire
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('products.create', [
            'categories' => Category::where('active', true)->orderBy('name')->get()
        ]);
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Création du produit
            $product = new Product([
                'name' => $this->name,
                'description' => $this->description,
                'barcode' => $this->barcode,
                'category_id' => $this->category_id ?: null,
                'price' => $this->price,
                'cost_price' => $this->cost_price,
                'vat_rate' => $this->vat_rate,
                'min_stock_alert' => $this->min_stock_alert,
                'track_stock' => $this->track_stock,
                'is_weighable' => $this->is_weighable,
                'has_free_price' => $this->has_free_price,
                'active' => $this->active,
            ]);

            // Gestion de l'image
            if ($this->image) {
                $imagePath = $this->image->store('products', 'public');
                $product->image_path = $imagePath;
            }

            $product->save();

            // Création du stock initial si nécessaire
            if ($this->track_stock && $this->initial_stock > 0) {
                $product->stocks()->create([
                    'type' => 'in',
                    'quantity' => $this->initial_stock,
                    'unit_price' => $this->cost_price,
                    'reference' => 'Initial stock',
                    'user_id' => auth()->id(),
                ]);
            }

            DB::commit();

            session()->flash('success', __('Product created successfully.'));
            return redirect()->route('products.index');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', __('Error creating product: ') . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('products.index');
    }

    public function updatedBarcode($value)
    {
        if (!$value) {
            $this->barcode = strtoupper(Str::random(10));
        }
    }

    public function generateBarcode()
    {
        $this->barcode = strtoupper(Str::random(10));
    }
} 