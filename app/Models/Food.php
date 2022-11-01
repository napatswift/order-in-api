<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $fillable = [
        'food_name',
        'food_price',
        'food_detail',
        'cooking_time',
        'restaurant_id',
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

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
