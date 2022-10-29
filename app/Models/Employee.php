<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends User
{
    use HasFactory;

    protected $table = 'users';

    public static function boot()
    {
        parent::boot();
 
        static::addGlobalScope(function ($query) {
            $query
                ->where('is_employee', true)
                ->where('is_manager', false);
        });
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
