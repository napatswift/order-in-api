<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
    ];

    public function orderDescription()
    {
        return $this->hasMany(OrderDescription::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
