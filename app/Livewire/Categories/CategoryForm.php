<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Str;

class CategoryForm extends Component
{
    public $category;
    public $categoryId;
    public $name;
    public $description;
    public $color = '#3b82f6'; // Default blue color
    public $icon;
    public $sort_order = 0;
    public $parent_id;
    public $active = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'color' => 'nullable|string|max:7',
        'icon' => 'nullable|string|max:50',
        'sort_order' => 'nullable|integer',
        'parent_id' => [
            'nullable',
            'exists:categories,id',
        ],
        'active' => 'boolean',
    ];

    public function mount($category = null)
    {
        if ($category) {
            $this->category = $category;
            $this->categoryId = $category->id;
            $this->name = $category->name;
            $this->description = $category->description;
            $this->color = $category->color;
            $this->icon = $category->icon;
            $this->sort_order = $category->sort_order;
            $this->parent_id = $category->parent_id;
            $this->active = $category->active;

            $this->rules['parent_id'][] = function ($attribute, $value, $fail) use ($category) {
                if ($value == $category->id) {
                    $fail(__('Category cannot be its own parent.'));
                }
            };
        }
    }

    public function updatedName()
    {
        // Auto-generate slug when name changes
        if (!$this->categoryId) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function save()
    {
        $validatedData = $this->validate();
        $validatedData['slug'] = Str::slug($this->name);

        if ($this->categoryId) {
            $category = Category::findOrFail($this->categoryId);
            $category->update($validatedData);
            $message = __('Category updated successfully');
        } else {
            Category::create($validatedData);
            $message = __('Category created successfully');
        }

        $this->dispatch('notify', [
            'message' => $message,
            'type' => 'success'
        ]);

        return redirect()->route('categories.index');
    }

    public function render()
    {
        $parentCategories = Category::where('active', true)
            ->when($this->categoryId, function ($query) {
                $query->where('id', '!=', $this->categoryId);
            })
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('livewire.categories.category-form', [
            'parentCategories' => $parentCategories,
            'availableIcons' => $this->getAvailableIcons(),
        ]);
    }

    protected function getAvailableIcons()
    {
        // Return a list of available icons (you can customize this list)
        return [
            'shopping-cart' => __('Shopping Cart'),
            'tag' => __('Tag'),
            'box' => __('Box'),
            'archive' => __('Archive'),
            'gift' => __('Gift'),
            'heart' => __('Heart'),
            'star' => __('Star'),
            'home' => __('Home'),
            'users' => __('Users'),
            'settings' => __('Settings'),
        ];
    }
} 