<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'notes'
    ];

    // Relations
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    // Scopes
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    // Accessors & Mutators
    public function getSalesCountAttribute()
    {
        return $this->sales()->count();
    }

    public function getTotalSpentAttribute()
    {
        return $this->sales()->completed()->sum('total_amount');
    }

    public function getFormattedTotalSpentAttribute()
    {
        return money($this->total_spent);
    }

    public function getLastPurchaseAttribute()
    {
        return $this->sales()->latest()->first();
    }

    public function getLastPurchaseDateAttribute()
    {
        return optional($this->last_purchase)->created_at;
    }
} 