<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IrfanProduct extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'irfan_category_id'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(IrfanCategory::class, 'irfan_category_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(IrfanOrderItem::class, 'irfan_product_id');
    }
}
