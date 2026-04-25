<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = 'payment_id';

    protected $fillable = [
    'order_id',
    'amount',
    'payment_method',
    'stripe_payment_id',
    'payment_status',
    'payment_date'
];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}
// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

// class Payment extends Model
// {
//     protected $primaryKey = 'payment_id';

//     protected $fillable = [
//         'order_id',
//         'amount',
//         'payment_date',
//         'payment_method',
//         'payment_status'
//     ];


//     public function order()
// {
//     return $this->belongsTo(Order::class, 'order_id', 'order_id');
// }
// }