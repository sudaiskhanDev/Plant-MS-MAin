<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    protected $primaryKey = 'plant_id';

    protected $fillable = [
        'name',
        'category_id',
        'price',
        'stock_quantity',
        'description',
        'image'
    ];

    // 🔗 Relationship
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }


    
    public function orderDetails()
{
    return $this->hasMany(OrderDetail::class, 'plant_id');
}
}