<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoyaltyProgram extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'type',
        'points_per_currency',
        'minimum_purchase',
        'start_date',
        'end_date',
        'active'
    ];

    protected $casts = [
        'points_per_currency' => 'decimal:2',
        'minimum_purchase' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'active' => 'boolean'
    ];
} 