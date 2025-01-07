<?php

namespace App\Livewire\Pos;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Terminal extends Component
{
    public $search = '';
    public $selectedCategory = '';
    public $selectedCustomer = '';
    public $paymentMethod = '';
    public $receivedAmount = 0;
    public $notes = '';
    public Collection $cartItems;
    public $subtotal = 0;
    public $tax = 0;
    public $total = 0;
    public $change = 0;

    public function mount()
    {
        $this->cartItems = collect();
    }

    public function render()
    {
        return view('pos.terminal', [
            'products' => $this->products,
            'categories' => $this->categories,
            'customers' => $this->customers,
        ])->layout('components.layouts.pos');
    }

    public function getProductsProperty()
    {
        $query = Product::query()
            ->with('category')
            ->where('active', true);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('barcode', 'like', "%{$this->search}%");
            });
        }

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        return $query->get();
    }

    public function getCategoriesProperty()
    {
        return Category::where('active', true)->get();
    }

    public function getCustomersProperty()
    {
        return Customer::where('active', true)->orderBy('last_name')->get();
    }

    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);
        
        if ($product->track_stock && $product->current_stock <= 0) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => __('Product is out of stock')
            ]);
            return;
        }

        $existingItem = $this->cartItems->firstWhere('id', $product->id);

        if ($existingItem) {
            if ($product->track_stock && $product->current_stock < $existingItem['quantity'] + 1) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => __('Insufficient stock. Available: :stock', ['stock' => $product->current_stock])
                ]);
                return;
            }
            
            $existingItem['quantity']++;
            $this->cartItems = $this->cartItems->map(function ($item) use ($existingItem) {
                return $item['id'] === $existingItem['id'] ? $existingItem : $item;
            });
        } else {
            $this->cartItems->push([
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'vat_rate' => $product->vat_rate
            ]);
        }

        $this->calculateTotals();
    }

    public function updateCartItem($index, $quantity)
    {
        $item = $this->cartItems[$index];
        $product = Product::find($item['id']);

        if ($product->track_stock && $product->current_stock < $quantity) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => __('Insufficient stock. Available: :stock', ['stock' => $product->current_stock])
            ]);
            return;
        }

        $this->cartItems[$index]['quantity'] = $quantity;
        $this->calculateTotals();
    }

    public function removeFromCart($index)
    {
        $this->cartItems->forget($index);
        $this->cartItems = $this->cartItems->values();
        $this->calculateTotals();
    }

    public function clearCart()
    {
        $this->cartItems = collect();
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->subtotal = $this->cartItems->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $this->tax = $this->cartItems->sum(function ($item) {
            return ($item['price'] * $item['quantity']) * ($item['vat_rate'] / 100);
        });

        $this->total = $this->subtotal + $this->tax;
        $this->calculateChange();
    }

    public function updatedReceivedAmount()
    {
        $this->calculateChange();
    }

    public function calculateChange()
    {
        $this->change = max(0, floatval($this->receivedAmount) - $this->total);
    }

    public function completeSale()
    {
        if ($this->cartItems->isEmpty()) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => __('Cart is empty')
            ]);
            return;
        }

        if (!$this->paymentMethod) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => __('Please select a payment method')
            ]);
            return;
        }

        if ($this->paymentMethod === 'cash' && floatval($this->receivedAmount) < $this->total) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => __('Insufficient payment amount')
            ]);
            return;
        }

        try {
            DB::beginTransaction();

            $sale = Sale::create([
                'customer_id' => $this->selectedCustomer ?: null,
                'user_id' => auth()->id(),
                'subtotal' => $this->subtotal,
                'tax_amount' => $this->tax,
                'total_amount' => $this->total,
                'payment_method' => $this->paymentMethod,
                'payment_status' => 'paid',
                'notes' => $this->notes,
                'completed_at' => now()
            ]);

            foreach ($this->cartItems as $item) {
                $product = Product::find($item['id']);
                
                $sale->items()->create([
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'tax_rate' => $item['vat_rate'],
                    'tax_amount' => ($item['price'] * $item['quantity']) * ($item['vat_rate'] / 100),
                    'total_amount' => ($item['price'] * $item['quantity']) * (1 + $item['vat_rate'] / 100),
                    'product_data' => $product->only([
                        'name', 'barcode', 'description', 'vat_rate'
                    ])
                ]);

                if ($product->track_stock) {
                    $product->stocks()->create([
                        'type' => 'out',
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                        'reference' => "Sale #{$sale->id}",
                        'user_id' => auth()->id()
                    ]);
                }
            }

            DB::commit();

            $this->clearCart();
            $this->selectedCustomer = '';
            $this->paymentMethod = '';
            $this->receivedAmount = 0;
            $this->notes = '';

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => __('Sale completed successfully')
            ]);

            $this->redirect(route('pos.receipt', $sale));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => __('Error completing sale: :message', ['message' => $e->getMessage()])
            ]);
        }
    }
} 