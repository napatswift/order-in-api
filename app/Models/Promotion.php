<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'promotion_price',
        'start_date',
        'end_date'
    ];

    public function food()
    {
        return $this->belongsToMany(Food::class);
    }
}
