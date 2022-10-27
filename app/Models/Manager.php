<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    use HasFactory;

    public function restaurant()
    {
        return $this->hasOne(Restaurant::class, 'owner_id');
    }
}
