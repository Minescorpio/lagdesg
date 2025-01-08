<?php

namespace App\Livewire\Sales;

use App\Helpers\CurrencyHelper;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Create extends Component
{
    public $items = [];
    public $customer_id;
    public $payment_method;
    public $payment_amount;
    public $notes;

    public function calculateTotal()
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item['quantity'] * $item['unit_price'];
        }
        return CurrencyHelper::format($total);
    }

    public function calculateChange()
    {
        if (!$this->payment_amount) return CurrencyHelper::format(0);
        
        $total = collect($this->items)->sum(function ($item) {
            return $item['quantity'] * $item['unit_price'];
        });
        
        return CurrencyHelper::format(max(0, $this->payment_amount - $total));
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('sales.create', [
            'total' => $this->calculateTotal(),
            'change' => $this->calculateChange(),
            'customers' => Customer::orderBy('first_name')->get(),
            'products' => Product::where('active', true)->get()->map(function ($product) {
                $product->formatted_price = CurrencyHelper::format($product->price);
                return $product;
            })
        ]);
    }
} 