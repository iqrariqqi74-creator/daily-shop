<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'user_type',
        'quantity',
        'product_id',
        'product_attribute_id',
        'added_on',
    ];
}