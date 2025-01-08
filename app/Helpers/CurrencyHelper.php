<?php

namespace App\Helpers;

class CurrencyHelper
{
    public static function format($amount)
    {
        return number_format($amount, 2, ',', ' ') . ' €';
    }
} 