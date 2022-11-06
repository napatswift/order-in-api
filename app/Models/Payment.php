<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_method',
        'date_payment',
        'customer_id',
    ];

    /**
     * mapper from id to name
     */
    public $paymentType = [
        'เงินสด',
        'โอนเงิน',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
