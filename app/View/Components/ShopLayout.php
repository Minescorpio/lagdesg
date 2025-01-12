<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ShopLayout extends Component
{
    public function __construct()
    {
        //
    }

    public function render()
    {
        return view('layouts.shop');
    }
} 