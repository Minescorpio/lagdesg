<?php

namespace App\Http\Livewire\Pos;

use App\Models\Customer;
use Livewire\Component;

class Terminal extends Component
{
    public $cart = [];
    public $customer = null;
    public $customerId = null;
    public $subtotal = 0;
    public $discount = 0;
    public $total = 0;
    public $cartTotal = 0;

    protected $listeners = [
        'cartUpdated' => 'calculateTotals',
        'customerSelected' => 'setCustomer'
    ];

    public function mount()
    {
        $this->cart = session()->get('pos_cart', []);
        $this->customerId = session()->get('pos_customer_id');
        if ($this->customerId) {
            $this->customer = Customer::find($this->customerId);
        }
        $this->subtotal = 0;
        $this->discount = 0;
        $this->total = 0;
        $this->cartTotal = 0;
        $this->calculateTotals();
    }

    public function setCustomer($customerId)
    {
        $this->customerId = $customerId;
        $this->customer = Customer::find($customerId);
        session()->put('pos_customer_id', $customerId);
    }

    public function calculateTotals()
    {
        $this->subtotal = collect($this->cart)->sum('total');
        $this->total = $this->subtotal - $this->discount;
        $this->cartTotal = $this->total;
    }

    public function getCartItemsCountProperty()
    {
        return collect($this->cart)->sum('quantity');
    }

    public function clearCart()
    {
        $this->cart = [];
        session()->put('pos_cart', []);
        $this->calculateTotals();
    }

    public function removeFromCart($productId)
    {
        $this->cart = collect($this->cart)->reject(function ($item) use ($productId) {
            return $item['id'] == $productId;
        })->values()->all();
        
        session()->put('pos_cart', $this->cart);
        $this->calculateTotals();
    }

    public function incrementQuantity($productId)
    {
        $this->cart = collect($this->cart)->map(function ($item) use ($productId) {
            if ($item['id'] == $productId) {
                $item['quantity']++;
                $item['total'] = $item['quantity'] * $item['price'];
            }
            return $item;
        })->all();

        session()->put('pos_cart', $this->cart);
        $this->calculateTotals();
    }

    public function decrementQuantity($productId)
    {
        $this->cart = collect($this->cart)->map(function ($item) use ($productId) {
            if ($item['id'] == $productId && $item['quantity'] > 1) {
                $item['quantity']--;
                $item['total'] = $item['quantity'] * $item['price'];
            }
            return $item;
        })->all();

        session()->put('pos_cart', $this->cart);
        $this->calculateTotals();
    }

    public function openCustomerModal()
    {
        $this->emit('openCustomerModal');
    }

    public function openDiscountModal()
    {
        $this->emit('openDiscountModal');
    }

    public function openPaymentModal()
    {
        $this->emit('openPaymentModal');
    }

    public function render()
    {
        return view('livewire.pos.terminal');
    }
} 