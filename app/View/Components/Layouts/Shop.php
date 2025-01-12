<?php

namespace App\View\Components\Layouts;

use App\Models\Category;
use Illuminate\View\Component;

class Shop extends Component
{
    public $categories;

    public function __construct()
    {
        $this->categories = Category::where('active', true)->orderBy('name')->get();
    }

    public function render()
    {
        return view('components.layouts.shop');
    }
}
