<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ProductForm extends Component
{
    use WithFileUploads;

    public $product;
    public $productId;
    public $name;
    public $barcode;
    public $description;
    public $price;
    public $cost_price;
    public $category_id;
    public $is_weighable = false;
    public $has_free_price = false;
    public $image;
    public $track_stock = true;
    public $min_stock_alert = 0;
    public $vat_rate = 20;
    public $active = true;
    public $currentImage;

    protected $rules = [
        'name' => 'required|string|max:255',
        'barcode' => 'nullable|string|unique:products,barcode',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'cost_price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'is_weighable' => 'boolean',
        'has_free_price' => 'boolean',
        'image' => 'nullable|image|max:2048',
        'track_stock' => 'boolean',
        'min_stock_alert' => 'nullable|integer|min:0',
        'vat_rate' => 'required|numeric|min:0|max:100',
        'active' => 'boolean',
    ];

    public function mount($product = null)
    {
        if ($product) {
            $this->product = $product;
            $this->productId = $product->id;
            $this->name = $product->name;
            $this->barcode = $product->barcode;
            $this->description = $product->description;
            $this->price = $product->price;
            $this->cost_price = $product->cost_price;
            $this->category_id = $product->category_id;
            $this->is_weighable = $product->is_weighable;
            $this->has_free_price = $product->has_free_price;
            $this->currentImage = $product->image_path;
            $this->track_stock = $product->track_stock;
            $this->min_stock_alert = $product->min_stock_alert;
            $this->vat_rate = $product->vat_rate;
            $this->active = $product->active;

            $this->rules['barcode'] = 'nullable|string|unique:products,barcode,' . $product->id;
        }
    }

    public function updatedImage()
    {
        $this->validate([
            'image' => 'image|max:2048'
        ]);
    }

    public function save()
    {
        $validatedData = $this->validate();

        if ($this->image) {
            $validatedData['image_path'] = $this->image->store('products', 'public');
            
            if ($this->currentImage) {
                Storage::disk('public')->delete($this->currentImage);
            }
        }

        if ($this->productId) {
            $product = Product::findOrFail($this->productId);
            $product->update($validatedData);
            $message = __('Product updated successfully');
        } else {
            Product::create($validatedData);
            $message = __('Product created successfully');
        }

        $this->dispatch('notify', [
            'message' => $message,
            'type' => 'success'
        ]);

        return redirect()->route('products.index');
    }

    public function deleteImage()
    {
        if ($this->currentImage) {
            Storage::disk('public')->delete($this->currentImage);
            $this->product->update(['image_path' => null]);
            $this->currentImage = null;
        }

        $this->dispatch('notify', [
            'message' => __('Image deleted successfully'),
            'type' => 'success'
        ]);
    }

    public function render()
    {
        $categories = Category::where('active', true)
            ->orderBy('name')
            ->get();

        return view('livewire.products.product-form', [
            'categories' => $categories
        ]);
    }

    public function generateBarcode()
    {
        do {
            $barcode = 'P' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        } while (Product::where('barcode', $barcode)->exists());

        $this->barcode = $barcode;
    }
} 