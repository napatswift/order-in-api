<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Food extends Model implements HasMedia
{
    use InteractsWithMedia;
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

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(200)
            //   ->height(232)
              ->sharpen(10);
    }

}
