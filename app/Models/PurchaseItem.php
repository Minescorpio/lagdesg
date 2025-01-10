<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'name',
        'description',
        'purchase_price',
        'estimated_resale_price',
        'condition',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
