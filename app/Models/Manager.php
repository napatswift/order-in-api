<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Manager extends User
{
    use HasFactory;

    protected $table = 'users';

    public static function boot()
    {
        parent::boot();
 
        static::addGlobalScope(function ($query) {
            $query
                ->where('is_employee', false)
                ->where('is_manager', true);
        });
    }

    public function restaurant()
    {
        return $this->hasOne(Restaurant::class, 'owner_id');
    }
}
