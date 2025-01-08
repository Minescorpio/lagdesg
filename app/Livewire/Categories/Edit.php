<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;

#[Title('Modifier la catégorie')]
class Edit extends Component
{
    public Category $category;
    public $name = '';
    public $description = '';
    public $parent_id = null;
    public $is_active = true;
    public $productsCount = 0;
    public $childrenCount = 0;

    public function mount(Category $category)
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->parent_id = $category->parent_id;
        $this->is_active = $category->is_active;
        $this->productsCount = $category->products()->count();
        $this->childrenCount = $category->children()->count();
    }

    public function rules()
    {
        return [
            'name' => 'required|min:3|max:255|unique:categories,name,' . $this->category->id,
            'description' => 'nullable|max:1000',
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                function ($attribute, $value, $fail) {
                    if ($value == $this->category->id) {
                        $fail(__('Une catégorie ne peut pas être son propre parent.'));
                    }
                },
            ],
            'is_active' => 'boolean',
        ];
    }

    public function updatedName()
    {
        $this->category->slug = Str::slug($this->name);
    }

    public function save()
    {
        $this->validate();

        $this->category->update([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'description' => $this->description,
            'parent_id' => $this->parent_id,
            'is_active' => $this->is_active,
        ]);

        session()->flash('success', __('Catégorie mise à jour avec succès.'));
        
        return $this->redirect(route('categories.index'), navigate: true);
    }

    public function render()
    {
        $categories = Category::where('id', '!=', $this->category->id)
            ->active()
            ->orderBy('name')
            ->get();

        return view('livewire.categories.edit', [
            'categories' => $categories
        ]);
    }
} 