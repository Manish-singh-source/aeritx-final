<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSellerPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'seller_id',
        'seller_price',
        'quantity',
        'assigned_by',
        'submitted_at',
    ];

    protected $casts = [
        'seller_price' => 'decimal:8',
        'quantity' => 'integer',
        'submitted_at' => 'datetime',
    ];

    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function seller() : BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }
}
