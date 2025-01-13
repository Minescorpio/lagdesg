<?php

namespace App\Livewire\Fournisseurs;

use App\Models\Fournisseur;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $showDeleteModal = false;
    public $fournisseurToDelete;
    public $showCreateForm = false;

    // Form Properties
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

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($fournisseurId)
    {
        $this->fournisseurToDelete = Fournisseur::findOrFail($fournisseurId);
        $this->showDeleteModal = true;
    }

    public function deleteFournisseur()
    {
        if ($this->fournisseurToDelete) {
            if ($this->fournisseurToDelete->products()->count() > 0) {
                $this->dispatch('notify', [
                    'message' => __('Cannot delete a supplier with associated products'),
                    'type' => 'error'
                ]);
                return;
            }

            $this->fournisseurToDelete->delete();
            $this->showDeleteModal = false;
            $this->fournisseurToDelete = null;

            $this->dispatch('notify', [
                'message' => __('Supplier deleted successfully'),
                'type' => 'success'
            ]);
        }
    }

    public function showCreate()
    {
        $this->resetValidation();
        $this->reset(['nom', 'email', 'telephone', 'adresse', 'notes']);
        $this->showCreateForm = true;
    }

    public function cancelCreate()
    {
        $this->showCreateForm = false;
    }

    public function save()
    {
        $this->validate();

        try {
            Fournisseur::create([
                'nom' => $this->nom,
                'email' => $this->email,
                'telephone' => $this->telephone,
                'adresse' => $this->adresse,
                'notes' => $this->notes,
            ]);

            $this->showCreateForm = false;
            $this->reset(['nom', 'email', 'telephone', 'adresse', 'notes']);
            
            $this->dispatch('notify', [
                'message' => __('Supplier created successfully'),
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => __('Error creating supplier: ') . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $query = Fournisseur::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nom', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('telephone', 'like', '%' . $this->search . '%')
                        ->orWhere('adresse', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('nom');

        return view('livewire.fournisseurs.index', [
            'fournisseurs' => $query->paginate($this->perPage),
        ]);
    }
} 