<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends User
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
