<?php

namespace App\Livewire\Stock;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title('Gestion du stock')]
#[Layout('components.layouts.app')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.stock.index');
    }
}
