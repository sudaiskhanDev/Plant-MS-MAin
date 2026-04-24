<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $primaryKey = 'order_detail_id';

    protected $fillable = [
        'order_id',
        'plant_id',
        'quantity',
        'price'
    ];


    public function order()
{
    return $this->belongsTo(Order::class, 'order_id', 'order_id');
}

public function plant()
{
    return $this->belongsTo(Plant::class, 'plant_id', 'plant_id');
}
}