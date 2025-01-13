<?php

namespace App\Livewire\Products;

use App\Helpers\CurrencyHelper;
use App\Models\Product;
use App\Models\Category;
use App\Models\Fournisseur;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Create Product')]
class Create extends Component
{
    use WithFileUploads;

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

    public function formatPrice($value)
    {
        return CurrencyHelper::format($value);
    }

    public function render()
    {
        return view('livewire.products.create', [
            'categories' => Category::where('is_active', true)->orderBy('name')->get(),
            'fournisseurs' => Fournisseur::orderBy('nom')->get(),
            'formatted_price' => $this->formatPrice($this->price),
            'formatted_cost_price' => $this->formatPrice($this->cost_price)
        ])->layout('components.layouts.app', [
            'header' => view('components.page-header', [
                'title' => __('Create New Product'),
                'backUrl' => route('products.index'),
                'backLabel' => __('Back to List')
            ])
        ]);
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

            session()->flash('success', __('Product created successfully.'));
            return redirect()->route('products.index');

        } catch (\Exception $e) {
            session()->flash('error', __('Error creating product: ') . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('products.index');
    }
} 