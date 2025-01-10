<?php

namespace App\Livewire\Pos;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\WithPagination;

#[Title('Terminal de vente')]
#[Layout('components.layouts.app')]
class Terminal extends Component
{
    use WithPagination;

    public $cartItems = [];
    public $selectedCustomer = null;
    public $lastSale = null;
    public $cartTotal = 0;
    public $cartItemsCount = 0;

    // Modal states
    public $showCustomerModal = false;
    public $showPaymentModal = false;
    public $showDiscountModal = false;

    // Customer search
    public $customerSearch = '';
    
    // Payment related
    public $paymentMethod = '';
    public $receivedAmount = 0;
    public $notes = '';
    public $discount = 0;
    public $taxRate = 20;

    // Discount related
    public $discountType = 'fixed';
    public $discountAmount = 0;
    public $finalAmount = 0;
    public $savings = 0;

    #[On('customerSelected')]
    public function onCustomerSelected($customerId)
    {
        $this->selectedCustomer = Customer::find($customerId);
        $this->showCustomerModal = false;
    }

    #[On('discountApplied')]
    public function onDiscountApplied()
    {
        $this->calculateDiscountAndFinal();
    }

    public function mount()
    {
        $this->lastSale = Sale::latest()->first();
        $this->calculateCartTotals();
    }

    public function calculateCartTotals()
    {
        $this->cartTotal = collect($this->cartItems)->sum(function($item) {
            return $item->price * $item->quantity;
        });
        $this->cartItemsCount = collect($this->cartItems)->sum('quantity');
        $this->calculateDiscountAndFinal();
    }

    public function calculateDiscountAndFinal()
    {
        $subtotal = $this->getSubtotal();
        $tax = $this->getTax();
        $total = $subtotal + $tax;

        if ($this->discountType === 'percentage') {
            $this->discount = ($subtotal * $this->discountAmount) / 100;
        } else {
            $this->discount = $this->discountAmount;
        }

        $this->finalAmount = $total - $this->discount;
        $this->savings = $this->discount;
    }

    public function getSubtotal()
    {
        return $this->cartTotal;
    }

    public function getTax()
    {
        return $this->getSubtotal() * ($this->taxRate / 100);
    }

    public function getTotal()
    {
        return $this->getSubtotal() + $this->getTax() - $this->discount;
    }

    public function getChange()
    {
        if ($this->paymentMethod !== 'cash' || !$this->receivedAmount) {
            return 0;
        }
        return max(0, $this->receivedAmount - $this->getTotal());
    }

    public function clearCart()
    {
        $this->cartItems = [];
        $this->calculateCartTotals();
        $this->dispatch('cart-updated');
    }

    public function incrementQuantity($itemId)
    {
        if (isset($this->cartItems[$itemId])) {
            $this->cartItems[$itemId]->quantity++;
            $this->calculateCartTotals();
        }
    }

    public function decrementQuantity($itemId)
    {
        if (isset($this->cartItems[$itemId]) && $this->cartItems[$itemId]->quantity > 1) {
            $this->cartItems[$itemId]->quantity--;
            $this->calculateCartTotals();
        }
    }

    public function removeItem($itemId)
    {
        if (isset($this->cartItems[$itemId])) {
            unset($this->cartItems[$itemId]);
            $this->calculateCartTotals();
        }
    }

    public function openCustomerModal()
    {
        $this->showCustomerModal = true;
    }

    public function openPaymentModal()
    {
        if (empty($this->cartItems)) {
            $this->addError('cart', __('Le panier est vide'));
            return;
        }
        $this->showPaymentModal = true;
    }

    public function openDiscountModal()
    {
        if (empty($this->cartItems)) {
            $this->addError('cart', __('Le panier est vide'));
            return;
        }
        $this->discountType = 'fixed';
        $this->discountAmount = 0;
        $this->calculateDiscountAndFinal();
        $this->showDiscountModal = true;
    }

    public function applyDiscount()
    {
        $this->validate([
            'discountAmount' => 'required|numeric|min:0|' . ($this->discountType === 'percentage' ? 'max:100' : 'max:' . $this->getTotal()),
        ]);

        $this->calculateDiscountAndFinal();
        $this->showDiscountModal = false;
    }

    public function updatedDiscountType()
    {
        $this->discountAmount = 0;
        $this->calculateDiscountAndFinal();
    }

    public function updatedDiscountAmount()
    {
        $this->calculateDiscountAndFinal();
    }

    public function processPayment()
    {
        $this->validate([
            'paymentMethod' => 'required|in:cash,card,transfer',
            'receivedAmount' => 'required_if:paymentMethod,cash|numeric|min:' . $this->getTotal(),
        ]);

        try {
            $sale = Sale::create([
                'customer_id' => $this->selectedCustomer?->id,
                'user_id' => auth()->id(),
                'subtotal' => $this->getSubtotal(),
                'tax' => $this->getTax(),
                'discount' => $this->discount,
                'total_amount' => $this->getTotal(),
                'payment_method' => $this->paymentMethod,
                'received_amount' => $this->receivedAmount,
                'change_amount' => $this->getChange(),
                'notes' => $this->notes,
                'status' => 'completed'
            ]);

            foreach ($this->cartItems as $item) {
                $sale->items()->create([
                    'product_id' => $item->id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->price,
                    'total' => $item->price * $item->quantity
                ]);
            }

            $this->lastSale = $sale;
            $this->clearCart();
            $this->showPaymentModal = false;
            $this->dispatch('sale-completed', $sale->id);

        } catch (\Exception $e) {
            $this->addError('payment', __('Une erreur est survenue lors du traitement du paiement'));
        }
    }

    public function render()
    {
        $customers = [];
        if ($this->showCustomerModal) {
            $customers = Customer::when($this->customerSearch, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', "%{$this->customerSearch}%")
                        ->orWhere('email', 'like', "%{$this->customerSearch}%")
                        ->orWhere('phone', 'like', "%{$this->customerSearch}%");
                });
            })->paginate(10);
        }

        return view('livewire.pos.terminal', [
            'customers' => $customers,
            'subtotal' => $this->getSubtotal(),
            'tax' => $this->getTax(),
            'total' => $this->getTotal(),
            'change' => $this->getChange(),
        ]);
    }
} 