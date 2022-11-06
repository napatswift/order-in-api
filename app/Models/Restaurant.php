<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'owner_id',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function food()
    {
        return $this->hasMany(Food::class);
    }

    public function manager()
    {
        return $this->belongsTo(Manager::class, 'owner_id');
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    public function tables()
    {
        return $this->hasMany(Table::class);
    }

    public function review()
    {
        return $this->hasMany(Review::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
