<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'user_id',
        'payment_method',
        'total_amount',
        'tax_amount',
        'amount_received',
        'change_amount',
        'notes',
        'status'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'amount_received' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    // Relations
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'sale_items')
            ->withPivot('quantity', 'price', 'subtotal')
            ->withTimestamps();
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeVoided($query)
    {
        return $query->where('status', 'voided');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    // Accessors & Mutators
    public function getFormattedTotalAttribute()
    {
        return money($this->total_amount);
    }

    public function getFormattedTaxAttribute()
    {
        return money($this->tax_amount);
    }

    public function getFormattedAmountReceivedAttribute()
    {
        return money($this->amount_received);
    }

    public function getFormattedChangeAttribute()
    {
        return money($this->change_amount);
    }

    public function getStatusColorAttribute()
    {
        return [
            'completed' => 'green',
            'voided' => 'red',
            'pending' => 'yellow',
        ][$this->status] ?? 'gray';
    }

    public function getPaymentMethodLabelAttribute()
    {
        return [
            'cash' => __('Cash'),
            'card' => __('Card'),
            'transfer' => __('Bank Transfer'),
        ][$this->payment_method] ?? $this->payment_method;
    }
}
