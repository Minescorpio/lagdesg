<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'price',
        'subtotal'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relations
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessors & Mutators
    public function getFormattedPriceAttribute()
    {
        return money($this->price);
    }

    public function getFormattedSubtotalAttribute()
    {
        return money($this->subtotal);
    }

    // Boot method to set subtotal before saving
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($saleItem) {
            $saleItem->subtotal = $saleItem->quantity * $saleItem->price;
        });
    }
}
