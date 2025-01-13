<?php

namespace App\Livewire\Fournisseurs;

use App\Models\Fournisseur;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Show extends Component
{
    public Fournisseur $fournisseur;

    public function mount(Fournisseur $fournisseur)
    {
        $this->fournisseur = $fournisseur;
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.fournisseurs.show', [
            'fournisseur' => $this->fournisseur->load('products'),
        ]);
    }
} 