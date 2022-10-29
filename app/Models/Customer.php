<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'users';

    public static function boot()
    {
        parent::boot();
 
        static::addGlobalScope(function ($query) {
            $query
                ->where('is_employee', false)
                ->where('is_manager', false);
        });
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
