<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Create extends Component
{
    public $name = '';
    public $description = '';
    public $parent_id = '';
    public $active = true;

    protected $rules = [
        'name' => 'required|string|max:255|unique:categories,name',
        'description' => 'nullable|string',
        'parent_id' => 'nullable|exists:categories,id',
        'active' => 'boolean'
    ];

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('categories.create', [
            'categories' => Category::where('active', true)
                ->where(function($query) {
                    $query->whereNull('parent_id')
                        ->orWhere('parent_id', '!=', $this->parent_id);
                })
                ->orderBy('name')
                ->get()
        ]);
    }

    public function save()
    {
        $this->validate();

        try {
            Category::create([
                'name' => $this->name,
                'description' => $this->description,
                'parent_id' => $this->parent_id ?: null,
                'active' => $this->active
            ]);

            session()->flash('success', __('Category created successfully.'));
            return redirect()->route('categories.index');

        } catch (\Exception $e) {
            session()->flash('error', __('Error creating category: ') . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('categories.index');
    }
} 