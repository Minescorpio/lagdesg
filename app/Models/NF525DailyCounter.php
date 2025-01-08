<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NF525DailyCounter extends Model
{
    protected $fillable = [
        'date',
        'sales_count',
        'cancelled_sales_count',
        'total_amount',
        'total_tax',
        'total_cancelled',
        'daily_hash'
    ];

    protected $casts = [
        'date' => 'date',
        'sales_count' => 'integer',
        'cancelled_sales_count' => 'integer',
        'total_amount' => 'decimal:2',
        'total_tax' => 'decimal:2',
        'total_cancelled' => 'decimal:2'
    ];
} 