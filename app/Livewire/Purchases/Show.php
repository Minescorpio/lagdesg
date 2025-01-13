<?php

namespace App\Livewire\Purchases;

use App\Models\Purchase;
use Livewire\Component;

class Show extends Component
{
    public Purchase $purchase;

    public function mount(Purchase $purchase)
    {
        $this->purchase = $purchase;
    }

    public function updateStatus($status)
    {
        $this->validate([
            'status' => 'required|in:pending,ordered,received,cancelled'
        ]);

        $this->purchase->update(['status' => $status]);
        session()->flash('success', __('Purchase order status updated successfully.'));
    }

    public function render()
    {
        return view('livewire.purchases.show');
    }
} 