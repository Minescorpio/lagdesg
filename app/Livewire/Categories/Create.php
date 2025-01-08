<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

class Create extends Component
{
    public $name = '';
    public $description = '';
    public $active = true;

    protected $rules = [
        'name' => 'required|string|max:255|unique:categories,name',
        'description' => 'nullable|string',
        'active' => 'boolean'
    ];

    #[Layout('layouts.app')]
    public function render()
    {
        return view('categories.create');
    }

    public function save()
    {
        $validated = $this->validate();
        $validated['slug'] = Str::slug($this->name);

        try {
            Category::create($validated);

            session()->flash('success', __('Catégorie créée avec succès.'));
            return $this->redirect(route('categories.index'), navigate: true);

        } catch (\Exception $e) {
            session()->flash('error', __('Erreur lors de la création de la catégorie : ') . $e->getMessage());
        }
    }

    public function cancel()
    {
        return $this->redirect(route('categories.index'), navigate: true);
    }
} 