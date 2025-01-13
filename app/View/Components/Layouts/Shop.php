<?php

namespace App\View\Components\Layouts;

use App\Models\Category;
use Illuminate\View\Component;
use Illuminate\View\View;

class Shop extends Component
{
    public $categories;

    public function __construct()
    {
        $this->categories = Category::active()->orderBy('name')->get();
    }

    public function render(): View
    {
        return view('components.layouts.shop');
    }
}
