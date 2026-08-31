<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Transaction extends Model
{

  use SoftDeletes;


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
    'transaction_date_time',
    'transaction_number',
    'transaction_type',
];


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
