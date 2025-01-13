<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'price',
        'cost_price',
        'stock',
        'minimum_stock',
        'barcode',
        'sku',
        'image',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'stock' => 'integer',
        'minimum_stock' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relations
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class, 'sale_items')
            ->withPivot('quantity', 'price', 'subtotal')
            ->withTimestamps();
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'minimum_stock');
    }

    // Accessors & Mutators
    public function getStockStatusAttribute()
    {
        if ($this->stock <= 0) {
            return 'out_of_stock';
        } elseif ($this->stock <= $this->minimum_stock) {
            return 'low_stock';
        }
        return 'in_stock';
    }

    public function getFormattedPriceAttribute()
    {
        return money($this->price);
    }

    public function getFormattedCostPriceAttribute()
    {
        return money($this->cost_price);
    }

    public function getProfitAttribute()
    {
        return $this->price - $this->cost_price;
    }

    public function getProfitPercentageAttribute()
    {
        if ($this->cost_price == 0) return 0;
        return ($this->profit / $this->cost_price) * 100;
    }
} 