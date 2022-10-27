<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function promotion()
    {
        return $this->hasMany(Promotion::class);
    }

    public function tables()
    {
        return $this->hasMany(Table::class);
    }
}
