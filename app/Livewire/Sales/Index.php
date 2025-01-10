<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $dateRange = 'today';
    public $paymentMethod = '';
    public $showDeleteModal = false;
    public $showReceiptModal = false;
    public $saleToDelete;
    public $selectedSale;

    #[Layout('components.layouts.app')]
    public function render()
    {
        $query = Sale::query()
            ->with(['customer', 'items.product'])
            ->withCount('items')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('receipt_no', 'like', '%' . $this->search . '%')
                        ->orWhereHas('customer', function ($q) {
                            $q->where('first_name', 'like', '%' . $this->search . '%')
                                ->orWhere('last_name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%')
                                ->orWhere('phone', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->paymentMethod, function ($query) {
                $query->where('payment_method', $this->paymentMethod);
            })
            ->when($this->dateRange, function ($query) {
                switch ($this->dateRange) {
                    case 'today':
                        $query->whereDate('created_at', Carbon::today());
                        break;
                    case 'yesterday':
                        $query->whereDate('created_at', Carbon::yesterday());
                        break;
                    case 'last7days':
                        $query->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()]);
                        break;
                    case 'last30days':
                        $query->whereBetween('created_at', [Carbon::now()->subDays(30), Carbon::now()]);
                        break;
                    case 'thisMonth':
                        $query->whereMonth('created_at', Carbon::now()->month)
                            ->whereYear('created_at', Carbon::now()->year);
                        break;
                    case 'lastMonth':
                        $query->whereMonth('created_at', Carbon::now()->subMonth()->month)
                            ->whereYear('created_at', Carbon::now()->subMonth()->year);
                        break;
                }
            })
            ->latest();

        $sales = $query->paginate(10);

        // Calculate statistics
        $statsQuery = clone $query;
        $totalSales = $statsQuery->sum('total_amount');
        $totalOrders = $sales->total();
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;
        $totalTax = $statsQuery->sum('tax');

        return view('livewire.sales.index', [
            'sales' => $sales,
            'totalSales' => $totalSales,
            'totalOrders' => $totalOrders,
            'averageOrderValue' => $averageOrderValue,
            'totalTax' => $totalTax,
        ]);
    }

    public function confirmDelete($saleId)
    {
        $this->saleToDelete = $saleId;
        $this->showDeleteModal = true;
    }

    public function deleteSale()
    {
        $sale = Sale::findOrFail($this->saleToDelete);
        $sale->delete();

        $this->showDeleteModal = false;
        $this->saleToDelete = null;

        session()->flash('success', __('Sale deleted successfully.'));
    }

    public function showReceipt($saleId)
    {
        $this->selectedSale = Sale::with(['customer', 'items.product'])->findOrFail($saleId);
        $this->showReceiptModal = true;
    }

    public function printReceipt($saleId)
    {
        // Implement receipt printing logic here
        session()->flash('success', __('Receipt printed successfully.'));
        $this->showReceiptModal = false;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDateRange()
    {
        $this->resetPage();
    }

    public function updatingPaymentMethod()
    {
        $this->resetPage();
    }
}
