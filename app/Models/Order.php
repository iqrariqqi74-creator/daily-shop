<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'status',
        'customer_id',
        'vendor_id',
        'created_by',
        'updated_by',
        'payment_method',
        'service_type',
        'amount',
        'promo_code',
        'order_start_date',
        'order_end_date',
        'order_number',
        'txn_id',

    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function orderDetails()
    {
        return $this->hasMany(
            OrderDetail::class,
            'order_id',
            'id'
        );
    }
}
