<?php

namespace App\Livewire\Fournisseurs;

use App\Models\Fournisseur;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Create extends Component
{
    public $nom;
    public $email;
    public $telephone;
    public $adresse;
    public $notes;

    protected $rules = [
        'nom' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'telephone' => 'nullable|string|max:20',
        'adresse' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
    ];

    public function save()
    {
        $this->validate();

        try {
            $fournisseur = Fournisseur::create([
                'nom' => $this->nom,
                'email' => $this->email,
                'telephone' => $this->telephone,
                'adresse' => $this->adresse,
                'notes' => $this->notes,
            ]);

            $this->dispatch('notify', [
                'message' => __('Supplier created successfully'),
                'type' => 'success'
            ]);

            return $this->redirect(route('fournisseurs.index'), navigate: true);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => __('Error creating supplier: ') . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    #[Title('Create Supplier')]
    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.fournisseurs.create');
    }
} 