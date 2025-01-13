<?php

namespace App\Livewire\Purchases;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Product;
use Livewire\Component;

class Create extends Component
{
    public $number;
    public $fournisseur_id;
    public $status = 'pending';
    public $items = [];
    public $total = 0;
    public $note;

    public function mount()
    {
        $this->number = 'PO-' . date('Ymd') . '-' . str_pad(Purchase::count() + 1, 4, '0', STR_PAD_LEFT);
        $this->items = [
            ['product_id' => '', 'quantity' => 1, 'price' => 0]
        ];
    }

    public function addItem()
    {
        $this->items[] = ['product_id' => '', 'quantity' => 1, 'price' => 0];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotal();
    }

    public function updatedItems()
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = collect($this->items)->sum(function ($item) {
            return $item['quantity'] * $item['price'];
        });
    }

    public function save()
    {
        $this->validate([
            'number' => 'required|unique:purchases,number',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'status' => 'required|in:pending,ordered,received,cancelled',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:1000',
        ]);

        $purchase = Purchase::create([
            'number' => $this->number,
            'fournisseur_id' => $this->fournisseur_id,
            'status' => $this->status,
            'total' => $this->total,
            'note' => $this->note,
        ]);

        foreach ($this->items as $item) {
            $purchase->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['quantity'] * $item['price'],
            ]);
        }

        session()->flash('success', __('Purchase order created successfully.'));
        return redirect()->route('purchases.show', $purchase);
    }

    public function render()
    {
        return view('livewire.purchases.create', [
            'suppliers' => Supplier::orderBy('nom')->get(),
            'products' => Product::orderBy('name')->get(),
        ]);
    }
} 