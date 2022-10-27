<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $fillable = [
        'food_name',
        'food_type',
        'food_price',
        'food_detail',
        'food_allergy',
        'cooking_time'
    ];

    public function foodAllergies()
    {
        return $this->belongsToMany(FoodAllergy::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function promotion()
    {
        return $this->belongsToMany(Promotion::class);
    }
}
