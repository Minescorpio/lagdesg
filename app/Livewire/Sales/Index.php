<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $dateRange = '';
    public $status = '';
    public $paymentMethod = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $confirmingSaleDeletion = false;
    public $saleIdToDelete;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'dateRange' => ['except' => ''],
        'status' => ['except' => ''],
        'paymentMethod' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDateRange()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingPaymentMethod()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmDelete($saleId)
    {
        $this->saleIdToDelete = $saleId;
        $this->confirmingSaleDeletion = true;
    }

    public function voidSale()
    {
        $sale = Sale::findOrFail($this->saleIdToDelete);
        
        // Check if sale can be voided
        if ($sale->status === 'voided') {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => __('Sale is already voided.')
            ]);
            return;
        }

        // Void the sale
        $sale->status = 'voided';
        $sale->save();

        // Restore stock for each item
        foreach ($sale->items as $item) {
            if ($item->product && $item->product->track_stock) {
                $item->product->stocks()->create([
                    'type' => 'in',
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'reference' => "Void Sale #{$sale->id}",
                    'user_id' => auth()->id()
                ]);
            }
        }

        $this->confirmingSaleDeletion = false;
        
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => __('Sale voided successfully.')
        ]);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $sales = Sale::query()
            ->with(['customer', 'items.product'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('reference', 'like', '%' . $this->search . '%')
                      ->orWhereHas('customer', function ($q) {
                          $q->where('first_name', 'like', '%' . $this->search . '%')
                            ->orWhere('last_name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->dateRange, function ($query) {
                $dates = explode(' - ', $this->dateRange);
                if (count($dates) === 2) {
                    $query->whereBetween('created_at', [
                        \Carbon\Carbon::parse($dates[0])->startOfDay(),
                        \Carbon\Carbon::parse($dates[1])->endOfDay(),
                    ]);
                }
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->paymentMethod, function ($query) {
                $query->where('payment_method', $this->paymentMethod);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('sales.index', [
            'sales' => $sales,
        ]);
    }
} 