<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id',
        'product_attribute_id',
        'quantity',
        'price',
        'total_price',
    ];

    public function order()
    {
        return $this->belongsTo(
            Order::class,
            'order_id',
            'id'
        );
    }

    public function productAttribute()
    {
        return $this->belongsTo(
            ProductAttribute::class,
            'product_attribute_id',
            'id'
        );
    }
}