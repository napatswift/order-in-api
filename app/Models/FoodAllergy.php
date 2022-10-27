<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodAllergy extends Model
{
    use HasFactory;

    protected $fillable = [
        'food_allergies',
    ];

    public function food()
    {
        return $this->belongsToMany(Food::class);
    }
}
