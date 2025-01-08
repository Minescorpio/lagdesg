<?php

namespace App\Livewire\Pos;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Terminal de vente')]
class Terminal extends Component
{
    public $cartTotal = 0;
    public $cartItemsCount = 0;
    public $selectedCustomer = null;
    public $lastSale = null;
    public $cart = [];
    public $products = [];

    public function mount()
    {
        $this->loadProducts();
        $this->loadLastSale();
        $this->calculateCartTotals();
    }

    public function loadProducts()
    {
        $this->products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function loadLastSale()
    {
        $this->lastSale = Sale::latest()->first();
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);
        
        if (!$product) return;

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity']++;
        } else {
            $this->cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1
            ];
        }

        $this->calculateCartTotals();
    }

    public function removeFromCart($productId)
    {
        if (isset($this->cart[$productId])) {
            unset($this->cart[$productId]);
            $this->calculateCartTotals();
        }
    }

    public function incrementQuantity($productId)
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity']++;
            $this->calculateCartTotals();
        }
    }

    public function decrementQuantity($productId)
    {
        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['quantity'] > 1) {
                $this->cart[$productId]['quantity']--;
            } else {
                unset($this->cart[$productId]);
            }
            $this->calculateCartTotals();
        }
    }

    public function calculateCartTotals()
    {
        $this->cartTotal = 0;
        $this->cartItemsCount = 0;

        foreach ($this->cart as $item) {
            $this->cartTotal += $item['price'] * $item['quantity'];
            $this->cartItemsCount += $item['quantity'];
        }
    }

    public function checkout()
    {
        if (empty($this->cart)) return;

        // Logique de paiement à implémenter
    }

    public function render()
    {
        return view('livewire.pos.terminal');
    }
} 