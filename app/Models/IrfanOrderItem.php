<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IrfanOrderItem extends Model
{
    protected $fillable = [
        'irfan_order_id',
        'irfan_product_id',
        'quantity',
        'price',
        'subtotal'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(IrfanOrder::class, 'irfan_order_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(IrfanProduct::class, 'irfan_product_id');
    }
}
