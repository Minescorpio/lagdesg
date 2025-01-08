<?php

namespace App\Livewire\Products;

use App\Helpers\CurrencyHelper;
use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

class Edit extends Component
{
    use WithFileUploads;

    public Product $product;
    
    // Basic Information
    public $name;
    public $description;
    public $barcode;
    public $category_id;
    
    // Price & Stock
    public $price;
    public $cost_price;
    public $vat_rate;
    public $track_stock;
    public $alert_quantity;
    
    // Additional Options
    public $weighable;
    public $free_price;
    public $active;
    
    // Image
    public $image;
    public $current_image;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'barcode' => 'nullable|string|max:50|unique:products,barcode,' . $this->product->id,
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'vat_rate' => 'required|numeric|min:0|max:100',
            'track_stock' => 'boolean',
            'alert_quantity' => 'required_if:track_stock,true|numeric|min:0',
            'weighable' => 'boolean',
            'free_price' => 'boolean',
            'active' => 'boolean',
            'image' => 'nullable|image|max:1024'
        ];
    }

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->barcode = $product->barcode;
        $this->category_id = $product->category_id;
        $this->price = $product->price;
        $this->cost_price = $product->cost_price;
        $this->vat_rate = $product->vat_rate;
        $this->track_stock = $product->track_stock;
        $this->alert_quantity = $product->alert_quantity;
        $this->weighable = $product->weighable;
        $this->free_price = $product->free_price;
        $this->active = $product->active;
        $this->current_image = $product->getFirstMediaUrl('product_images');
    }

    public function formatPrice($value)
    {
        return CurrencyHelper::format($value);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('products.edit', [
            'categories' => Category::where('active', true)->orderBy('name')->get(),
            'formatted_price' => $this->formatPrice($this->price),
            'formatted_cost_price' => $this->formatPrice($this->cost_price)
        ]);
    }

    public function save()
    {
        $this->validate();

        try {
            $this->product->update([
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
                'active' => $this->active
            ]);

            if ($this->image) {
                $this->product->clearMediaCollection('product_images');
                $this->product->addMedia($this->image)->toMediaCollection('product_images');
            }

            session()->flash('success', __('Product updated successfully.'));
            return redirect()->route('products.index');

        } catch (\Exception $e) {
            session()->flash('error', __('Error updating product: ') . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('products.index');
    }
} 