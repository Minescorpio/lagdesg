<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyaltyReward extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'points_earned',
        'points_used',
        'sale_id',
        'description',
        'type',
    ];

    protected $casts = [
        'points_earned' => 'integer',
        'points_used' => 'integer',
    ];

    /**
     * Get the customer that owns the loyalty reward.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the sale associated with this reward.
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
} 