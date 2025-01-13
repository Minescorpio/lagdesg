<?php

namespace App\Livewire\POS;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Terminal extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $customerId = '';
    public $paymentMethod = 'cash';
    public $amountReceived = 0;
    public $notes = '';
    public $showSuccessModal = false;
    public $lastSaleId = null;
    public $taxRate = 10; // 10% tax rate

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
    ];

    public function getSubtotalProperty()
    {
        return collect(session('cart', []))->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    }

    public function getTaxProperty()
    {
        return $this->subtotal * ($this->taxRate / 100);
    }

    public function getTotalProperty()
    {
        return $this->subtotal + $this->tax;
    }

    public function getChangeProperty()
    {
        if ($this->paymentMethod !== 'cash' || !is_numeric($this->amountReceived)) {
            return 0;
        }
        return max(0, $this->amountReceived - $this->total);
    }

    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);
        
        if ($product->stock < 1) {
            $this->dispatch('error', ['message' => __('Product is out of stock.')]);
            return;
        }

        $cart = session()->get('cart', []);
        
        if (isset($cart[$product->id])) {
            if ($cart[$product->id]['quantity'] + 1 > $product->stock) {
                $this->dispatch('error', ['message' => __('Not enough stock available.')]);
                return;
            }
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1
            ];
        }

        session()->put('cart', $cart);
        $this->dispatch('success', ['message' => __('Product added to cart successfully.')]);
    }

    public function removeFromCart($productId)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
            $this->dispatch('success', ['message' => __('Product removed from cart successfully.')]);
        }
    }

    public function updateQuantity($productId, $quantity)
    {
        if ($quantity < 1) {
            $this->removeFromCart($productId);
            return;
        }

        $product = Product::findOrFail($productId);
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            if ($quantity > $product->stock) {
                $this->dispatch('error', ['message' => __('Not enough stock available.')]);
                return;
            }

            $cart[$productId]['quantity'] = $quantity;
            session()->put('cart', $cart);
            $this->dispatch('success', ['message' => __('Cart updated successfully.')]);
        }
    }

    public function clearCart()
    {
        session()->forget('cart');
        $this->dispatch('success', ['message' => __('Cart cleared successfully.')]);
    }

    public function processSale()
    {
        if ($this->paymentMethod === 'cash' && $this->amountReceived < $this->total) {
            $this->dispatch('error', ['message' => __('Insufficient amount received.')]);
            return;
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            $this->dispatch('error', ['message' => __('Cart is empty.')]);
            return;
        }

        try {
            DB::beginTransaction();

            // Create sale
            $sale = Sale::create([
                'customer_id' => $this->customerId ?: null,
                'payment_method' => $this->paymentMethod,
                'amount_received' => $this->amountReceived,
                'total_amount' => $this->total,
                'tax_amount' => $this->tax,
                'change_amount' => $this->change,
                'notes' => $this->notes,
                'status' => 'completed'
            ]);

            // Create sale items and update stock
            foreach ($cart as $item) {
                $product = Product::findOrFail($item['id']);
                
                if ($product->stock < $item['quantity']) {
                    throw new \Exception(__('Not enough stock for :product', ['product' => $product->name]));
                }

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity']
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();

            // Clear cart and reset form
            session()->forget('cart');
            $this->reset(['customerId', 'paymentMethod', 'amountReceived', 'notes']);
            $this->lastSaleId = $sale->id;
            $this->showSuccessModal = true;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', ['message' => $e->getMessage()]);
        }
    }

    public function render()
    {
        $categories = Category::where('is_active', true)->get();
        $customers = Customer::all();
        
        $productsQuery = Product::with('category')
            ->where('is_active', true);

        if ($this->search) {
            $productsQuery->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->categoryFilter) {
            $productsQuery->where('category_id', $this->categoryFilter);
        }

        $products = $productsQuery->paginate(12);

        return view('livewire.pos.terminal', [
            'categories' => $categories,
            'customers' => $customers,
            'products' => $products,
            'cart' => session()->get('cart', [])
        ]);
    }
} 