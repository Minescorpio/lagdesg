<?php

namespace App\Models;

use App\Helpers\CurrencyHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'description',
        'barcode',
        'min_stock_alert',
        'vat_rate',
        'price',
        'cost_price',
        'category_id',
        'track_stock',
        'active',
        'is_weighable',
        'has_free_price',
        'image_path'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'min_stock_alert' => 'integer',
        'vat_rate' => 'decimal:2',
        'track_stock' => 'boolean',
        'active' => 'boolean',
        'is_weighable' => 'boolean',
        'has_free_price' => 'boolean'
    ];

    protected $appends = [
        'formatted_price',
        'formatted_cost_price',
        'current_stock'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function getFormattedPriceAttribute()
    {
        return CurrencyHelper::format($this->price);
    }

    public function getFormattedCostPriceAttribute()
    {
        return CurrencyHelper::format($this->cost_price);
    }

    public function getCurrentStockAttribute(): float
    {
        return DB::table('stocks')
            ->where('product_id', $this->id)
            ->whereNull('deleted_at')
            ->sum(DB::raw('CASE 
                WHEN `type` = "in" THEN `quantity`
                WHEN `type` = "out" THEN -`quantity`
                WHEN `type` = "adjustment" THEN `quantity`
                ELSE 0 
            END')) ?? 0;
    }

    public function scopeLowStock($query)
    {
        $stockSubquery = DB::table('stocks')
            ->select('product_id')
            ->selectRaw('COALESCE(SUM(CASE 
                WHEN type = "in" THEN quantity
                WHEN type = "out" THEN -quantity
                WHEN type = "adjustment" THEN quantity
                ELSE 0 
            END), 0) as total_stock')
            ->whereNull('deleted_at')
            ->groupBy('product_id');

        return $query->select('products.*')
            ->leftJoinSub($stockSubquery, 'stock_totals', function($join) {
                $join->on('products.id', '=', 'stock_totals.product_id');
            })
            ->where('products.min_stock_alert', '>', 0)
            ->where(function($query) {
                $query->whereColumn('stock_totals.total_stock', '<=', 'products.min_stock_alert')
                    ->orWhereNull('stock_totals.total_stock');
            });
    }
} 