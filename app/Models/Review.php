<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'feedback',
        'restaurant_id'
    ];

    public function rating()
    {
        return $this->hasMany(Rating::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
