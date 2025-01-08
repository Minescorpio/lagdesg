<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showDeleteModal = false;
    public $customerToDelete;

    #[Layout('layouts.app')]
    public function render()
    {
        $customers = Customer::query()
            ->withCount('sales')
            ->withSum('sales', 'total_amount as total_spent')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%')
                        ->orWhere('customer_code', 'like', '%' . $this->search . '%');
                });
            })
            ->orderByName()
            ->paginate(10);

        return view('livewire.customers.index', [
            'customers' => $customers
        ]);
    }

    public function confirmDelete($customerId)
    {
        $this->customerToDelete = $customerId;
        $this->showDeleteModal = true;
    }

    public function deleteCustomer()
    {
        $customer = Customer::findOrFail($this->customerToDelete);
        $customer->delete();

        $this->showDeleteModal = false;
        $this->customerToDelete = null;

        session()->flash('success', __('Customer deleted successfully.'));
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
} 