<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'food_id',
        'order_quantity',
        'order_status',
        'order_request',
        'order_price'
    ];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeStatusString()
    {
        return 'hello';
        return $this->statusName[$this->order_status];
    }
}
