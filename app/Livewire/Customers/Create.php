<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $phone = '';
    public $address = '';
    public $postal_code = '';
    public $city = '';
    public $notes = '';
    public $active = true;
    public $accepts_marketing = false;

    public function mount()
    {
        // Initialisation des valeurs par défaut si nécessaire
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.customers.customer-form', [
            'customerId' => null,
            'countries' => [
                'FR' => 'France',
                'BE' => 'Belgique',
                'CH' => 'Suisse',
                'CA' => 'Canada',
            ]
        ]);
    }

    public function save()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'active' => 'boolean',
            'accepts_marketing' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $customer = Customer::create([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'postal_code' => $this->postal_code,
                'city' => $this->city,
                'notes' => $this->notes,
                'active' => $this->active,
                'accepts_marketing' => $this->accepts_marketing,
            ]);

            DB::commit();

            session()->flash('success', __('Client créé avec succès.'));
            return $this->redirect(route('customers.index'), navigate: true);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', __('Erreur lors de la création du client: ') . $e->getMessage());
        }
    }

    public function updatedPostalCode($value)
    {
        // Formatage automatique du code postal (France)
        $this->postal_code = preg_replace('/[^0-9]/', '', $value);
    }

    public function updatedPhone($value)
    {
        // Formatage automatique du numéro de téléphone (France)
        $phone = preg_replace('/[^0-9+]/', '', $value);
        if (strlen($phone) === 10 && substr($phone, 0, 1) === '0') {
            $phone = '+33' . substr($phone, 1);
        }
        $this->phone = $phone;
    }
} 