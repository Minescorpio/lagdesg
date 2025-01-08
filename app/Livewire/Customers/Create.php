<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use App\Models\LoyaltyProgram;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    // Informations personnelles
    public $title = '';
    public $first_name = '';
    public $last_name = '';
    public $birth_date;
    
    // Coordonnées
    public $email = '';
    public $phone = '';
    public $address = '';
    public $postal_code = '';
    public $city = '';
    
    // Options
    public $loyalty_program = '';
    public $notes = '';
    public $active = true;
    public $accepts_marketing = false;

    protected $rules = [
        'title' => 'nullable|string|in:mr,mrs,ms',
        'first_name' => 'required|string|max:100',
        'last_name' => 'required|string|max:100',
        'birth_date' => 'nullable|date',
        'email' => 'nullable|email|max:255|unique:customers,email',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:255',
        'postal_code' => 'nullable|string|max:10',
        'city' => 'nullable|string|max:100',
        'loyalty_program' => 'nullable|exists:loyalty_programs,id',
        'notes' => 'nullable|string',
        'active' => 'boolean',
        'accepts_marketing' => 'boolean'
    ];

    public function mount()
    {
        // Initialisation si nécessaire
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('customers.create', [
            'loyaltyPrograms' => LoyaltyProgram::where('active', true)->get()
        ]);
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $customer = Customer::create([
                'title' => $this->title,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'birth_date' => $this->birth_date,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'postal_code' => $this->postal_code,
                'city' => $this->city,
                'loyalty_program_id' => $this->loyalty_program ?: null,
                'notes' => $this->notes,
                'active' => $this->active,
                'accepts_marketing' => $this->accepts_marketing,
                'loyalty_points' => 0
            ]);

            DB::commit();

            session()->flash('success', __('Customer created successfully.'));
            return redirect()->route('customers.index');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', __('Error creating customer: ') . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('customers.index');
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