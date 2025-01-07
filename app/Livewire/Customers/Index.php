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
    public $perPage = 10;
    public $sortField = 'last_name';
    public $sortDirection = 'asc';
    public $confirmingCustomerDeletion = false;
    public $customerId;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'last_name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function updatingSearch()
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

    public function confirmDelete($customerId)
    {
        $this->customerId = $customerId;
        $this->confirmingCustomerDeletion = true;
    }

    public function deleteCustomer()
    {
        $customer = Customer::findOrFail($this->customerId);
        $customer->delete();
        $this->confirmingCustomerDeletion = false;
        
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => __('Customer deleted successfully.')
        ]);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $customers = Customer::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('customers.index', [
            'customers' => $customers,
        ]);
    }
} 