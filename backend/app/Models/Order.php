<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id';

    protected $fillable = [
    'user_id',
    'order_date',
    'status',
    'total_amount',
    'payment_method',
    'name',
    'phone',
    'city',
    'zip',
    'shipping_address',
];
    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'user_id', 'user_id');
    // }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'order_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'order_id');
    }
}
// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

// class Order extends Model
// {
//     protected $primaryKey = 'order_id';

//     protected $fillable = [
//         'order_date',
//         'total_amount',
//         'status',
//         'user_id'
//     ];

//      public function user()
// {
//     return $this->belongsTo(User::class, 'user_id', 'user_id');
// }

// public function orderDetails()
// {
//     return $this->hasMany(OrderDetail::class, 'order_id', 'order_id');
// }

// public function payment()
// {
//     return $this->hasOne(Payment::class, 'order_id', 'order_id');
// }

    
// }