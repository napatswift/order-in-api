<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Manager extends Model
{
    use HasFactory;

    public function restaurant()
    {
        return $this->hasOne(Relation::class);
    }
}
