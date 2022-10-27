<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Relation;

class Manager extends User
{
    use HasFactory;

    public function restautant()
    {
        return $this->hasOne(Relation::class);
    }
}
