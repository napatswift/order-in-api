<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'count',
        'name'
    ];

    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
