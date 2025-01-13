<?php

namespace App\Livewire\Purchases;

use App\Models\Purchase;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $perPage = 10;
    public $showDeleteModal = false;
    public $purchaseIdToDelete;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function confirmDelete($purchaseId)
    {
        $this->purchaseIdToDelete = $purchaseId;
        $this->showDeleteModal = true;
    }

    public function deletePurchase()
    {
        $purchase = Purchase::findOrFail($this->purchaseIdToDelete);
        $purchase->delete();

        $this->showDeleteModal = false;
        $this->purchaseIdToDelete = null;

        session()->flash('success', __('Purchase order deleted successfully.'));
    }

    public function render()
    {
        $purchases = Purchase::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('number', 'like', '%' . $this->search . '%')
                        ->orWhereHas('supplier', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.purchases.index', [
            'purchases' => $purchases,
        ]);
    }
} 