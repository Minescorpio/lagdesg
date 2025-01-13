<?php

namespace App\Livewire\Sales;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use App\Models\LoyaltyProgram;
use App\Models\Sale;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Collection;

class PosTerminal extends Component
{
    use WithPagination;

    // Search and filters
    public $search = '';
    public $selectedCategory = '';
    public $showFavorites = false;

    // Cart
    public $cart = [];
    public $customer = null;
    public $customerId = null;
    public $paymentMethod = 'cash';
    public $paymentDetails = [];
    public $loyaltyPointsToUse = 0;
    public $notes = '';

    // Calculations
    public $subtotal = 0;
    public $taxAmount = 0;
    public $discountAmount = 0;
    public $totalAmount = 0;

    protected $listeners = [
        'productSelected' => 'addToCart',
        'customerSelected' => 'setCustomer',
        'paymentProcessed' => 'processSale'
    ];

    public function mount()
    {
        $this->cart = collect([]);
    }

    public function render()
    {
        $query = Product::query()
            ->where('is_active', true)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('barcode', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->when($this->showFavorites, function ($query) {
                $query->where('is_favorite', true);
            });

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('livewire.sales.pos-terminal', [
            'products' => $query->paginate(12),
            'categories' => $categories,
            'availablePrograms' => $this->getAvailableLoyaltyPrograms(),
        ]);
    }

    public function addToCart($productId, $quantity = 1)
    {
        $product = Product::findOrFail($productId);
        
        if ($product->track_stock && $product->current_stock < $quantity) {
            $this->dispatch('notify', [
                'message' => __('Insufficient stock for this product.'),
                'type' => 'error'
            ]);
            return;
        }

        $cartItem = $this->cart->firstWhere('product_id', $productId);

        if ($cartItem) {
            $cartItem['quantity'] += $quantity;
            $cartItem['total'] = $cartItem['quantity'] * $cartItem['unit_price'];
        } else {
            $this->cart->push([
                'product_id' => $product->id,
                'name' => $product->name,
                'unit_price' => $product->price,
                'quantity' => $quantity,
                'total' => $product->price * $quantity,
                'vat_rate' => $product->vat_rate,
                'is_weighable' => $product->is_weighable,
            ]);
        }

        $this->calculateTotals();
    }

    public function removeFromCart($index)
    {
        $this->cart->forget($index);
        $this->calculateTotals();
    }

    public function updateQuantity($index, $quantity)
    {
        if ($quantity <= 0) {
            $this->removeFromCart($index);
            return;
        }

        $item = $this->cart[$index];
        $product = Product::find($item['product_id']);

        if ($product->track_stock && $product->current_stock < $quantity) {
            $this->dispatch('notify', [
                'message' => __('Insufficient stock for this product.'),
                'type' => 'error'
            ]);
            return;
        }

        $this->cart[$index]['quantity'] = $quantity;
        $this->cart[$index]['total'] = $quantity * $item['unit_price'];
        
        $this->calculateTotals();
    }

    public function setCustomer($customerId)
    {
        $this->customer = $customerId ? Customer::find($customerId) : null;
        $this->customerId = $customerId;
        $this->calculateTotals(); // Recalculate in case of customer-specific pricing
    }

    public function calculateTotals()
    {
        $this->subtotal = $this->cart->sum('total');
        $this->taxAmount = $this->cart->sum(function ($item) {
            return ($item['total'] * $item['vat_rate']) / 100;
        });
        
        // Apply loyalty program discounts if applicable
        if ($this->customer && $this->loyaltyPointsToUse > 0) {
            $program = $this->getAvailableLoyaltyPrograms()->first();
            if ($program) {
                $this->discountAmount = min(
                    $this->loyaltyPointsToUse * $program->reward_value,
                    $this->subtotal
                );
            }
        }

        $this->totalAmount = $this->subtotal + $this->taxAmount - $this->discountAmount;
    }

    public function processSale()
    {
        if ($this->cart->isEmpty()) {
            $this->dispatch('notify', [
                'message' => __('Cart is empty'),
                'type' => 'error'
            ]);
            return;
        }

        try {
            $sale = Sale::create([
                'customer_id' => $this->customerId,
                'user_id' => auth()->id(),
                'subtotal' => $this->subtotal,
                'tax_amount' => $this->taxAmount,
                'discount_amount' => $this->discountAmount,
                'total_amount' => $this->totalAmount,
                'payment_method' => $this->paymentMethod,
                'payment_details' => $this->paymentDetails,
                'loyalty_points_used' => $this->loyaltyPointsToUse,
                'notes' => $this->notes,
                'completed_at' => now(),
                'payment_status' => 'paid',
            ]);

            foreach ($this->cart as $item) {
                $product = Product::find($item['product_id']);
                
                $sale->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'tax_rate' => $item['vat_rate'],
                    'tax_amount' => ($item['total'] * $item['vat_rate']) / 100,
                    'total_amount' => $item['total'],
                    'product_data' => $product->only([
                        'name', 'barcode', 'description', 'vat_rate'
                    ]),
                ]);

                if ($product->track_stock) {
                    $product->decrement('current_stock', $item['quantity']);
                }
            }

            // Process loyalty points
            if ($this->customer) {
                if ($this->loyaltyPointsToUse > 0) {
                    $this->customer->decrement('loyalty_points', $this->loyaltyPointsToUse);
                }

                $program = $this->getAvailableLoyaltyPrograms()->first();
                if ($program && $this->totalAmount >= $program->minimum_purchase) {
                    $pointsEarned = floor($this->totalAmount * $program->points_per_currency);
                    $this->customer->increment('loyalty_points', $pointsEarned);
                    $sale->update(['loyalty_points_earned' => $pointsEarned]);
                }

                $this->customer->update(['last_purchase_at' => now()]);
            }

            $this->reset(['cart', 'customer', 'customerId', 'paymentMethod', 'paymentDetails', 
                'loyaltyPointsToUse', 'notes', 'subtotal', 'taxAmount', 'discountAmount', 'totalAmount']);

            $this->dispatch('saleCompleted', ['sale_id' => $sale->id]);
            
            $this->dispatch('notify', [
                'message' => __('Sale completed successfully'),
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => __('Error processing sale: ') . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    protected function getAvailableLoyaltyPrograms()
    {
        return LoyaltyProgram::where('active', true)
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->get();
    }
} 