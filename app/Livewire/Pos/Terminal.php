<?php

namespace App\Livewire\Pos;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use App\Models\SaleItem;
use App\Helpers\CurrencyHelper;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Terminal extends Component
{
    public $search = '';
    public $selectedCategory = '';
    public $selectedCustomer = '';
    public $paymentMethod = '';
    public $receivedAmount = 0;
    public $notes = '';
    public $cart = [];
    public $showReceiptModal = false;
    public $lastSale = null;

    protected $listeners = ['productSelected' => 'addToCart'];

    public function mount()
    {
        $this->cart = [];
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $query = Product::query()
            ->where('active', true)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('barcode', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('category_id', $this->selectedCategory);
            });

        return view('pos.terminal', [
            'products' => $query->get(),
            'categories' => Category::where('active', true)->orderBy('name')->get(),
            'customers' => Customer::orderBy('first_name')->get(),
            'subtotal' => CurrencyHelper::format($this->getSubtotal()),
            'tax' => CurrencyHelper::format($this->getTax()),
            'total' => CurrencyHelper::format($this->getTotal()),
            'change' => CurrencyHelper::format($this->getChange())
        ]);
    }

    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);

        if (!$product->active) {
            $this->addError('product', __('Ce produit n\'est pas disponible à la vente.'));
            return;
        }

        if ($product->track_stock && $product->current_stock <= 0) {
            $this->addError('product', __('Ce produit est en rupture de stock.'));
            return;
        }

        $existingItem = collect($this->cart)->firstWhere('id', $product->id);

        if ($existingItem) {
            if ($product->track_stock && $existingItem['quantity'] + 1 > $product->current_stock) {
                $this->addError('product', __('Stock insuffisant. Disponible: ') . $product->current_stock);
                return;
            }
            $existingItem['quantity']++;
            $existingItem['total'] = CurrencyHelper::format($existingItem['quantity'] * $product->price);
            $this->cart = collect($this->cart)->map(function ($item) use ($existingItem) {
                return $item['id'] === $existingItem['id'] ? $existingItem : $item;
            })->toArray();
        } else {
            $this->cart[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => CurrencyHelper::format($product->price),
                'quantity' => 1,
                'total' => CurrencyHelper::format($product->price)
            ];
        }
    }

    public function incrementQuantity($productId)
    {
        $product = Product::findOrFail($productId);
        $item = collect($this->cart)->firstWhere('id', $product->id);

        if ($product->track_stock && $item['quantity'] + 1 > $product->current_stock) {
            $this->addError('product', __('Stock insuffisant. Disponible: ') . $product->current_stock);
            return;
        }

        $this->cart = collect($this->cart)->map(function ($item) use ($product) {
            if ($item['id'] === $product->id) {
                $item['quantity']++;
                $item['total'] = CurrencyHelper::format($item['quantity'] * $product->price);
            }
            return $item;
        })->toArray();
    }

    public function decrementQuantity($productId)
    {
        $this->cart = collect($this->cart)->map(function ($item) use ($productId) {
            if ($item['id'] == $productId && $item['quantity'] > 1) {
                $item['quantity']--;
                $product = Product::find($productId);
                $item['total'] = CurrencyHelper::format($item['quantity'] * $product->price);
            }
            return $item;
        })->toArray();
    }

    public function removeFromCart($productId)
    {
        $this->cart = collect($this->cart)->reject(function ($item) use ($productId) {
            return $item['id'] == $productId;
        })->values()->toArray();
    }

    public function clearCart()
    {
        $this->cart = [];
        $this->paymentMethod = '';
        $this->receivedAmount = 0;
        $this->notes = '';
    }

    public function getSubtotal()
    {
        return collect($this->cart)->sum(function ($item) {
            $product = Product::find($item['id']);
            return $item['quantity'] * $product->price;
        });
    }

    public function getTax()
    {
        return collect($this->cart)->sum(function ($item) {
            $product = Product::find($item['id']);
            return $item['quantity'] * $product->price * ($product->vat_rate / 100);
        });
    }

    public function getTotal()
    {
        return $this->getSubtotal() + $this->getTax();
    }

    public function getChange()
    {
        if ($this->paymentMethod !== 'cash' || !$this->receivedAmount) {
            return 0;
        }

        return max(0, $this->receivedAmount - $this->getTotal());
    }

    public function completeSale()
    {
        if (empty($this->cart)) {
            $this->addError('cart', __('Le panier est vide.'));
            return;
        }

        if (empty($this->paymentMethod)) {
            $this->addError('payment', __('Veuillez sélectionner un mode de paiement.'));
            return;
        }

        if ($this->paymentMethod === 'cash' && $this->receivedAmount < $this->getTotal()) {
            $this->addError('payment', __('Le montant reçu est insuffisant.'));
            return;
        }

        try {
            $sale = Sale::create([
                'customer_id' => $this->selectedCustomer ?: null,
                'user_id' => auth()->id(),
                'subtotal' => $this->getSubtotal(),
                'tax' => $this->getTax(),
                'total_amount' => $this->getTotal(),
                'payment_method' => $this->paymentMethod,
                'received_amount' => $this->receivedAmount,
                'change_amount' => $this->getChange(),
                'notes' => $this->notes,
                'status' => 'completed'
            ]);

            foreach ($this->cart as $item) {
                $product = Product::find($item['id']);
                
                $saleItem = $sale->items()->create([
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $item['quantity'] * $product->price,
                    'tax' => $item['quantity'] * $product->price * ($product->vat_rate / 100),
                    'total' => $item['quantity'] * $product->price * (1 + $product->vat_rate / 100)
                ]);

                if ($product->track_stock) {
                    $product->stocks()->create([
                        'type' => 'out',
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->cost_price,
                        'reference' => 'Sale #' . $sale->id,
                        'user_id' => auth()->id()
                    ]);
                }
            }

            $this->lastSale = $sale->load('items.product', 'customer');
            $this->showReceiptModal = true;
            $this->clearCart();

        } catch (\Exception $e) {
            $this->addError('sale', __('Erreur lors de la finalisation de la vente: ') . $e->getMessage());
        }
    }

    public function printReceipt()
    {
        // Logique d'impression à implémenter
        $this->showReceiptModal = false;
    }
} 