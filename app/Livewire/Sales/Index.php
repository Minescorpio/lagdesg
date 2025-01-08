<?php

namespace App\Livewire\Sales;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sale;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $paymentMethodFilter = '';
    public $dateFilter = 'today';
    public $startDate;
    public $endDate;
    public $showDeleteModal = false;
    public $showReceiptModal = false;
    public $selectedSaleId;
    public $selectedSale;

    protected $queryString = [
        'search' => ['except' => ''],
        'paymentMethodFilter' => ['except' => ''],
        'dateFilter' => ['except' => 'today'],
    ];

    public function mount()
    {
        $this->startDate = now()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function updatedDateFilter()
    {
        if ($this->dateFilter === 'custom') {
            $this->startDate = now()->format('Y-m-d');
            $this->endDate = now()->format('Y-m-d');
        }
    }

    public function showReceipt($saleId)
    {
        $this->selectedSaleId = $saleId;
        $this->selectedSale = Sale::with(['items.product', 'customer'])->find($saleId);
        $this->showReceiptModal = true;
    }

    public function printReceipt()
    {
        $this->dispatch('print-receipt', saleId: $this->selectedSaleId);
    }

    public function confirmSaleDeletion($saleId)
    {
        $this->selectedSaleId = $saleId;
        $this->showDeleteModal = true;
    }

    public function deleteSale()
    {
        $sale = Sale::findOrFail($this->selectedSaleId);
        
        // Only allow deletion of today's sales
        if (!$sale->created_at->isToday()) {
            $this->addError('delete', __('Seules les ventes du jour peuvent être supprimées.'));
            return;
        }

        // Restore stock for each item
        foreach ($sale->items as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        $sale->delete();

        $this->showDeleteModal = false;
        $this->dispatch('sale-deleted');
        session()->flash('success', __('La vente a été supprimée avec succès.'));
    }

    public function getDateRange()
    {
        return match($this->dateFilter) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'yesterday' => [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()],
            'this_week' => [now()->startOfWeek(), now()->endOfWeek()],
            'this_month' => [now()->startOfMonth(), now()->endOfMonth()],
            'last_month' => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
            'this_year' => [now()->startOfYear(), now()->endOfYear()],
            'custom' => [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ],
            default => [now()->startOfDay(), now()->endOfDay()],
        };
    }

    public function render()
    {
        [$startDate, $endDate] = $this->getDateRange();

        $sales = Sale::query()
            ->with(['customer', 'items'])
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('receipt_number', 'like', '%' . $this->search . '%')
                        ->orWhereHas('customer', function ($query) {
                            $query->where('first_name', 'like', '%' . $this->search . '%')
                                ->orWhere('last_name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->paymentMethodFilter, function ($query) {
                $query->where('payment_method', $this->paymentMethodFilter);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->paginate(10);

        return view('sales.index', [
            'sales' => $sales
        ]);
    }
}
