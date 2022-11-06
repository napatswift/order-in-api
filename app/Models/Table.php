<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_number',
        'available',
        'restaurant_id'
    ];

    protected $casts = [
        'available' => 'boolean'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
