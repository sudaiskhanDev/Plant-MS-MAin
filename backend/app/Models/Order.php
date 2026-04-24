<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id';

    protected $fillable = [
        'user_id',
        'order_date',
        'total_amount',
        'status'
    ];

    // 🔗 relation
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ⚠️ next table ke liye ready
    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
}