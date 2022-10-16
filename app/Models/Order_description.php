<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_description extends Model
{
    use HasFactory;

    protected $fillable = [
        'food_id',
        'order_quantity',
        'order_status',
        'order_request',
        'order_price'
    ];
}
