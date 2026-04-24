<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Cart extends Model
{
    protected $primaryKey = 'cart_id';

    protected $fillable = [
        'user_id',
        'plant_id',
        'quantity'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id', 'plant_id');
    }
}


// class Cart extends Model
// {
//     protected $primaryKey = 'cart_id';

//     protected $fillable = [
//         'user_id',
//         'plant_id',
//         'quantity'
//     ];


//     //
//     public function user()
// {
//     return $this->belongsTo(User::class, 'user_id', 'user_id');
// }

// public function plant()
// {
//     return $this->belongsTo(Plant::class, 'plant_id', 'plant_id');
// }
// }