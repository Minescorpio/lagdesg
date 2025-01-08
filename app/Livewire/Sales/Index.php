<?php

namespace App\Livewire\Sales;

use App\Helpers\CurrencyHelper;
use App\Models\Sale;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $payment_method = '';
    public $date_range = '';

    #[Layout('components.layouts.app')]
    public function render()
    {
        $query = Sale::query()
            ->with('customer')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('receipt_number', 'like', '%' . $this->search . '%')
                      ->orWhereHas('customer', function ($q) {
                          $q->where('first_name', 'like', '%' . $this->search . '%')
                            ->orWhere('last_name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->payment_method, function ($query) {
                $query->where('payment_method', $this->payment_method);
            })
            ->latest();

        $sales = $query->paginate(10);
        
        // Format currency for each sale
        $sales->getCollection()->transform(function ($sale) {
            $sale->formatted_total = CurrencyHelper::format($sale->total_amount);
            return $sale;
        });

        return view('sales.index', [
            'sales' => $sales
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
